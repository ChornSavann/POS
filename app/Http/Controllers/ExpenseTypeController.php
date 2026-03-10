<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Request\ExpenseTypeRequest;
use App\Service\IService\IExpenseTypeService;

class ExpenseTypeController extends Controller
{
    protected $service;

    public function __construct(IExpenseTypeService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $expense_types = $this->service->getAllExpenseTypes($request);
        $formdata = $this->service->FormData(); // ហៅ Function ដែលបងទើបបង្ហាញ

        return view('expense_type.index', [
            'expense_types' => $expense_types,
            'banks'         => $formdata['banks'],       // កែពី 'bank' មក 'banks' ឱ្យត្រូវគ្នា
            'itemExpenses'  => $formdata['itemExpense'], // ផ្ទៀងផ្ទាត់ឈ្មោះឱ្យត្រូវគ្នា
        ]);
    }

   public function create()
    {
        $formdata = $this->service->FormData(); // ទាញយក Banks និង ItemExpenses សម្រាប់ Dropdown
        return view('expense_type.create', [
            'banks'         => $formdata['banks'],       // កែពី 'bank' មក 'banks' ឱ្យត្រូវគ្នា
            'itemExpenses'  => $formdata['itemExpense'], // ផ្ទៀងផ្ទាត់ឈ្មោះឱ្យត្រូវគ្នា
        ]);
    }
    public function store(ExpenseTypeRequest $request)
    {
        $this->service->createExpenseType($request->validated());

        return redirect()->route('expense_types.index')
                        ->with('success', 'រក្សាទុកបានជោគជ័យ!');
    }
    public function edit($id)
    {
        // ទាញយកទិន្នន័យដែលចង់កែ
        $expenseType = $this->service->getExpenseTypeById($id);

        // ទាញយកទិន្នន័យសម្រាប់ Dropdown (Banks & ItemExpenses)
        $formdata = $this->service->FormData();

        return view('expense_type.edit', [
            'expenseType'   => $expenseType,
            'banks'         => $formdata['banks'] ?? [],
            'itemExpenses'  => $formdata['itemExpense'] ?? [],
        ]);
    }
    public function update(ExpenseTypeRequest $request, $id)
    {
        $this->service->updateExpenseType($id, $request->validated());

        return redirect()->route('expense_types.index')
                        ->with('success', 'ធ្វើបច្ចុប្បន្នភាពជោគជ័យ!');
    }
    public function destroy($id)
    {
        try {
            $expenseType = $this->service->getExpenseTypeById($id);

            if (!$expenseType) {
                return redirect()->route('expense_types.index')
                                ->with('error', 'រកមិនឃើញទិន្នន័យដែលត្រូវលុបឡើយ!');
            }
            $this->service->deleteExpenseType($id);
            return redirect()->route('expense_types.index')
                            ->with('success', 'ប្រតិបត្តិការត្រូវបានលុបដោយជោគជ័យ!');

        } catch (\Exception $e) {
            // បើមាន Error បច្ចេកទេស (ឧទាហរណ៍៖ ទិន្នន័យជាប់ Foreign Key)
            return redirect()->route('expense_types.index')
                            ->with('error', 'មិនអាចលុបបានទេ៖ ' . $e->getMessage());
        }
    }
}
