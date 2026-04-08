<?php

namespace App\Http\Controllers;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        // ទាញយក Permission ទាំងអស់ហើយបែងចែកតាម group_name
        $permissions = Permission::all()->groupBy('group_name');
        return view('permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions',
            'label_kh' => 'required',
            'group_name' => 'required'
        ]);

        Permission::create($request->all());

        return redirect()->back()->with('success', 'រក្សាទុកជោគជ័យ');
    }
}
