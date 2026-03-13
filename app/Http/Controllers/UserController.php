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

    // Inject IUserService តាមរយៈ Constructor
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function login()
    {
        if (Auth::check()) {
            // ប្តូរមកប្រើ URL path ដែលបងបានកំណត់ក្នុង web.php
            return redirect()->intended('/');//dashboard
        }
        return view('users.login');
    }


    public function authenticate(UserRequest $request) {

        if ($this->userService->login($request->validated())) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }
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
        return redirect('/login');
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
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

        if (!$user) {
            return redirect()->route('users.index')->with('error', 'User not found.');
        }

        return view('users.edit', compact('user'));
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
