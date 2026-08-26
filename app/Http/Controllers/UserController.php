<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Service\IService\IUserService;
use Illuminate\Support\Facades\Log;
use App\Request\UserRequest;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    protected $userService;
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function login()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('users.login');
    }

    public function authenticate(UserRequest $request)
    {

        if ($this->userService->login($request->validated())) {

            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->hasRole('admin')) {
                return redirect()->intended('/dashboard');
            }

            if ($user->hasRole('cashier')) {
                return redirect()->intended('/dashboard');
            }

            // ប្រសិនបើជា User ធម្មតា ឬបុគ្គលិកផ្នែកឃ្លាំង
            if ($user->hasRole('user')) {
                return redirect()->intended('/dashboard');
            }

            // ទិសដៅចុងក្រោយ បើមិនចូលលក្ខខណ្ឌខាងលើ

            return redirect()->intended('/');
        }

        // ៤. ប្រសិនបើ Login បរាជ័យ
        return back()->withErrors([
            'email' => 'អ៊ីមែល ឬលេខសម្ងាត់មិនត្រឹមត្រូវ។',
        ])->onlyInput('email');
    }

    // public function register(Request $request)
    // {
    //     // ១. កំណត់លក្ខខណ្ឌ Validation
    //     $rules = [
    //         'name'     => 'required|string|max:255',
    //         'email'    => 'required|email|unique:users,email', // ឆែកកុំឱ្យស្ទួនក្នុង Table users
    //         'password' => 'required|min:8|confirmed',        // ត្រូវមាន input ឈ្មោះ password_confirmation
    //         'phone'    => 'nullable|string|max:20',
    //         'address'  => 'nullable|string|max:500',
    //         'role_id'  => 'nullable|exists:roles,id',        // បើមានការរើស Role ត្រូវឆែកថាមានក្នុង DB ពិតមែន
    //     ];

    //     // ២. បង្កើតសារបញ្ជាក់កំហុសជាភាសាខ្មែរ
    //     $messages = [
    //         'name.required'      => 'សូមបញ្ចូលឈ្មោះរបស់អ្នក។',
    //         'email.required'     => 'សូមបញ្ចូលអ៊ីមែល។',
    //         'email.email'        => 'ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវទេ។',
    //         'email.unique'       => 'អ៊ីមែលនេះត្រូវបានប្រើប្រាស់រួចហើយ។',
    //         'password.required'  => 'សូមបញ្ចូលលេខសម្ងាត់។',
    //         'password.min'       => 'លេខសម្ងាត់ត្រូវមានយ៉ាងតិច ៨ ខ្ទង់។',
    //         'password.confirmed' => 'ការបញ្ជាក់លេខសម្ងាត់មិនត្រឹមត្រូវទេ។',
    //     ];

    //     // ៣. ធ្វើការផ្ទៀងផ្ទាត់ (បើមិនត្រឹមត្រូវ វានឹង Redirect ទៅវិញដោយស្វ័យប្រវត្តិ)
    //     $validatedData = $request->validate($rules, $messages);

    //     try {
    //         // ៤. បញ្ជូនទិន្នន័យដែលបាន Validate រួចទៅកាន់ Service
    //         $user = $this->userService->registerUser($validatedData);

    //         if ($user) {
    //             // បងអាចដក dd($user) ចេញបានហើយ បើវាដើរជោគជ័យ
    //             return redirect()->to('/')
    //                             ->with('success', 'គណនីរបស់អ្នកត្រូវបានបង្កើតដោយជោគជ័យ!');
    //         }

    //     } catch (\Exception $e) {
    //         // ៥. បើមានបញ្ហាបច្ចេកទេស (ឧទាហរណ៍៖ DB Error)
    //         return back()->withInput()
    //                     ->withErrors(['email' => 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage()]);
    //     }

    //     return back()->withInput()
    //                 ->with('error', 'មានបញ្ហាបច្ចេកទេស! សូមព្យាយាមម្ដងទៀត។');
    // }

    public function register(Request $request)
    {
        // ១. កំណត់លក្ខខណ្ឌ Validation
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email', // ឆែកកុំឱ្យស្ទួនក្នុង Table users
            'password' => 'required|min:8|confirmed',        // ត្រូវមាន input ឈ្មោះ password_confirmation
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string|max:500',
            'role_id'  => 'nullable|exists:roles,id',        // បើមានការរើស Role ត្រូវឆែកថាមានក្នុង DB ពិតមែន
        ];

        // ២. បង្កើតសារបញ្ជាក់កំហុសជាភាសាខ្មែរ
        $messages = [
            'name.required'      => 'សូមបញ្ចូលឈ្មោះរបស់អ្នក។',
            'email.required'     => 'សូមបញ្ចូលអ៊ីមែល។',
            'email.email'        => 'ទម្រង់អ៊ីមែលមិនត្រឹមត្រូវទេ។',
            'email.unique'       => 'អ៊ីមែលនេះត្រូវបានប្រើប្រាស់រួចហើយ។',
            'password.required'  => 'សូមបញ្ចូលលេខសម្ងាត់។',
            'password.min'       => 'លេខសម្ងាត់ត្រូវមានយ៉ាងតិច ៨ ខ្ទង់។',
            'password.confirmed' => 'ការបញ្ជាក់លេខសម្ងាត់មិនត្រឹមត្រូវទេ។',
        ];

        // ៣. ធ្វើការផ្ទៀងផ្ទាត់ (បើមិនត្រឹមត្រូវ វានឹង Redirect ទៅវិញដោយស្វ័យប្រវត្តិ)
        $validatedData = $request->validate($rules, $messages);

        try {
            // ៤. បញ្ជូនទិន្នន័យដែលបាន Validate រួចទៅកាន់ Service
            $user = $this->userService->registerUser($validatedData);

            if ($user) {
                // ធ្វើការ Login ឱ្យ User នេះស្វ័យប្រវត្តិ ដើម្បីការពារបញ្ហា Middleware ទាត់ចេញ
                auth()->login($user);

                return redirect()->to('/')
                    ->with('success', 'គណនីរបស់អ្នកត្រូវបានបង្កើតដោយជោគជ័យ!');
            }
        } catch (\Exception $e) {
            // ៥. បើមានបញ្ហាបច្ចេកទេស (ឧទាហរណ៍៖ DB Error)
            return back()->withInput()
                ->withErrors(['email' => 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage()]);
        }

        return back()->withInput()
            ->with('error', 'មានបញ្ហាបច្ចេកទេស! សូមព្យាយាមម្ដងទៀត។');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'អ្នកបានចេញពីប្រព័ន្ធដោយជោគជ័យ!');
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = DB::table('roles')->get();
        return view('users.create', compact('roles'));
    }

    public function store(UserRequest $request)
    {
        // data is already clean here
        $user = $this->userService->createUser($request->validated());

        if ($user) {
            return redirect()->route('users.index')->with('success', 'User created successfully!');
        }
        return back()->with('error', 'Something went wrong while creating the user.');
    }


    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        $roles = DB::table('roles')->get();
        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        return view('users.edit', compact('user', 'roles'));
    }


    public function update(UserRequest $request, $id)
    {
        $result = $this->userService->updateUser($id, $request->validated());

        if ($result) {
            return redirect()->route('users.index')->with('success', 'User updated successfully!');
        }
        return back()->with('error', 'Update failed.');
    }


    public function destroy($id)
    {
        try {
            $deleted = $this->userService->DeleteUser($id);
            // dd($deleted);
            if ($deleted) {
                return redirect()->route('users.index')
                    ->with('success', 'លុបអ្នកប្រើប្រាស់បានជោគជ័យ!');
            }

            return redirect()->route('users.index')
                ->with('error', 'រកមិនឃើញអ្នកប្រើប្រាស់ ឬមិនអាចលុបបានឡើយ!');
        } catch (\Exception $e) {
            Log::error("Delete User Error ID {$id}: " . $e->getMessage());
            return redirect()->route('users.index')
                ->with('error', 'មានបញ្ហាបច្ចេកទេស៖ ' . $e->getMessage());
        }
    }
}
