<?php

namespace App\Http\Controllers;

use App\Models\CashSession;
use App\Models\Order; // ត្រូវប្រាកដថាបាន use Order Model
use Illuminate\Http\Request;
use Carbon\Carbon;

class CashsessionController extends Controller
{
    /**
     * បង្ហាញ Form បើកបញ្ជី (Opening Form)
     */
     public function report()
    {
        // ទាញយក Session ទាំងអស់ តម្រៀបពីថ្មីមកចាស់
        $sessions = CashSession::with('user')
                    ->orderBy('created_at', 'desc')
                    ->paginate(15);

        return view('report.cash_sessions', compact('sessions'));
    }
 public function create()
{
    $currentSession = \App\Models\CashSession::where('user_id', auth()->id())
                        ->where('status', 'open')
                        ->first();

    $system_cash = 0;
    $system_bank = 0;

    if ($currentSession) {
        // បូកសរុបលុយសុទ្ធ (CASH) ចេញពី Table OrderPayment
        $system_cash = \App\Models\OrderPayment::whereHas('order', function($q) use ($currentSession) {
                            $q->where('cash_session_id', $currentSession->id);
                        })
                        ->where('payment_method', 'CASH')
                        ->sum('paid_amount');

        // បូកសរុបលុយធនាគារ (Bank/QR) ចេញពី Table OrderPayment
        $system_bank = \App\Models\OrderPayment::whereHas('order', function($q) use ($currentSession) {
                            $q->where('cash_session_id', $currentSession->id);
                        })
                        ->whereIn('payment_method', ['ABA', 'WING', 'KHQR'])
                        ->sum('paid_amount');
    }

    return view('cash_session.create', compact('currentSession', 'system_cash', 'system_bank'));
}
    /**
     * រក្សាទុកទិន្នន័យពេលចាប់ផ្តើមបើកបញ្ជី
     */
    public function store(Request $request)
    {
        $request->validate([
            'opening_balance' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        CashSession::create([
            'user_id'         => auth()->id(),
            'opening_time'    => now(),
            'opening_balance' => $request->opening_balance,
            'status'          => 'open',
            'note'            => $request->note,
        ]);

        return redirect()->route('dashboard')->with('success', 'បញ្ជីត្រូវបានបើកដោយជោគជ័យ!');
    }

    /**
     * បង្ហាញ Form បិទបញ្ជី និងបូកសរុបលទ្ធផលលក់ (Closing Form)
     */
    public function edit()
    {
        $session = CashSession::where('user_id', auth()->id())
                            ->where('status', 'open')
                            ->first();

        if (!$session) {
            return redirect()->route('dashboard')->with('error', 'មិនមានបញ្ជីដែលកំពុងបើកទេ។');
        }

        // --- Logic បូកសរុបការលក់ក្នុង Session នេះ ---
        // ឧបមាថា orders table របស់បងមាន payment_method និង grand_total
        $salesQuery = Order::where('user_id', auth()->id())
                           ->whereBetween('created_at', [$session->opening_time, now()]);

        $system_cash = (clone $salesQuery)->where('payment_method', 'CASH')->sum('grand_total');
        $system_bank = (clone $salesQuery)->whereIn('payment_method', ['KHQR', 'WING', 'ABA'])->sum('grand_total');
        $system_discount = (clone $salesQuery)->sum('discount_amount'); // បើមាន

        return view('cash_sessions.edit', compact('session', 'system_cash', 'system_bank', 'system_discount'));
    }

    /**
     * រក្សាទុកទិន្នន័យពេលបិទបញ្ជី (Update Session)
     */
    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'actual_cash' => 'required|numeric|min:0',
    //         'note' => 'nullable|string',
    //     ]);

    //     $session = CashSession::findOrFail($id);

    //     // គណនាផលសង (Difference)
    //     // រូបមន្ត៖ លុយរាប់ឃើញ - (លុយដើមគ្រា + លុយលក់បានជាសាច់ប្រាក់)
    //     $expected_cash = $session->opening_balance + $request->system_cash;
    //     $difference = $request->actual_cash - $expected_cash;

    //     $session->update([
    //         'closing_time'    => now(),
    //         'system_cash'     => $request->system_cash,
    //         'system_bank'     => $request->system_bank,
    //         'system_discount' => $request->system_discount,
    //         'actual_cash'     => $request->actual_cash,
    //         'difference'      => $difference,
    //         'status'          => 'closed',
    //         'note'            => $request->note,
    //     ]);

    //     return redirect()->route('dashboard')->with('success', 'បញ្ជីត្រូវបានបិទ និងរក្សាទុកដោយជោគជ័យ!');
    // }
    public function update(Request $request, $id)
{
    // 1. ត្រួតពិនិត្យទិន្នន័យដែលបញ្ចូលមក
    $request->validate([
        'actual_cash' => 'required|numeric|min:0',
        'system_cash' => 'required|numeric',
        'system_bank' => 'required|numeric',
        'note'        => 'nullable|string',
    ]);

    // 2. ទាញយក Session ដែលកំពុងបើកមកកែប្រែ
    $session = \App\Models\CashSession::findOrFail($id);

    // 3. គណនាផលសង (Difference)
    // រូបមន្ត៖ លុយរាប់ឃើញជាក់ស្តែង - (លុយដើមគ្រា + លុយលក់បានជាសាច់ប្រាក់)
    $expected_cash = $session->opening_balance + $request->system_cash;
    $difference = $request->actual_cash - $expected_cash;

    // 4. Update ទិន្នន័យទៅក្នុង Database
    $session->update([
        'closing_time' => now(),
        'system_cash'  => $request->system_cash,
        'system_bank'  => $request->system_bank,
        'actual_cash'  => $request->actual_cash,
        'difference'   => $difference,
        'status'       => 'closed',
        'note'         => $request->note,
    ]);

    // 5. ត្រឡប់ទៅទំព័រដើមវិញជាមួយសារជោគជ័យ
    return redirect()->route('cash-session.create')->with('success', 'បញ្ជីត្រូវបានបិទ និងផ្ទៀងផ្ទាត់រួចរាល់!');
}
}
