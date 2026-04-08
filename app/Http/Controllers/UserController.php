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
        if (Auth::check())
        {
            // បើ Login ហើយ ឱ្យទៅកាន់ Dashboard ភ្លាម
            return redirect()->route('dashboard');
        }
        return view('users.login');
    }

    public function authenticate(UserRequest $request)
    {
        // ១. ព្យាយាម Login តាមរយៈ Service
       if ($this->userService->login($request->validated()))
        {
            // ២. បង្កើត Session ថ្មីដើម្បីសុវត្ថិភាព (Security)
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            // ប្រសិនបើជា Admin ឱ្យទៅកាន់ Dashboard ធំដើម្បីមើលរបាយការណ៍សរុប
            if ($user->hasRole('admin')) {
                return redirect()->intended('/dashboard');
            }

            // ប្រសិនបើជា Cashier (អ្នកកាន់លុយ) ឱ្យទៅកាន់ផ្ទាំងលក់ (POS) តែម្តងដើម្បីភាពរហ័ស
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

    public function register(UserRequest $request)
    {
        $this->userService->registerUser($request->validated());

        return redirect()->route('dashboard')
                     ->with('success', 'គណនីរបស់អ្នកត្រូវបានបង្កើតដោយជោគជ័យ!');
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
        $roles=DB::table('roles')->get();
        return view('users.create',compact('roles'));

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
            $roles=DB::table('roles')->get();
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
            $this->userService->deleteUser($id);
            return redirect()->route('users.index')->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            Log::error("Delete User Error: " . $e->getMessage());
            return redirect()->route('users.index')->with('error', 'Cannot delete user.');
        }
    }


}
