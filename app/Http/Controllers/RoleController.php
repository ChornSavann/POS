<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    // ១. បង្ហាញបញ្ជី Roles ទាំងអស់
    public function index()
    {
        // ប្តូរពី all() មកប្រើ paginate(ចំនួនជួរក្នុងមួយទំព័រ)
        $roles = Role::withCount('permissions')->paginate(10);

        return view('role.index', compact('roles'));
    }
    // ២. បង្ហាញ Form បង្កើត Role ថ្មី និងបញ្ជី Permission សម្រាប់គ្រីស
    public function create()
    {
        // បែងចែក Permission ជាក្រុមៗ ដើម្បីឱ្យងាយស្រួលមើលក្នុង Form
        $permissions = Permission::all()->groupBy('group_name');
        return view('role.create', compact('permissions'));
    }

    // ៣. រក្សាទុក Role និងសិទ្ធិដែលបានជ្រើសរើស
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
            'label_kh' => 'required',
            'permissions' => 'array' // ទទួលយកជា Array ពី Checkbox
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create([
                'name' => $request->name,
                'label_kh' => $request->label_kh,
            ]);

            // ចងភ្ជាប់សិទ្ធិទៅកាន់ Role ក្នុងតារាង permission_role
            if ($request->has('permissions')) {
                $role->permissions()->attach($request->permissions);
            }
        });

        return redirect()->route('roles.index')->with('success', 'បង្កើតតួនាទីដោយជោគជ័យ');
    }

    // ៤. បង្ហាញ Form កែសម្រួល Role និងសិទ្ធិដែលមានស្រាប់
    public function edit(Role $role)
    {
        $permissions = Permission::all()->groupBy('group_name');
        // ទាញយក ID នៃសិទ្ធិដែល Role នេះមានស្រាប់ ដើម្បីឱ្យវា Checked ក្នុង Form
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('role.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    // ៥. Update Role និងប្រើ sync() ដើម្បីធ្វើបច្ចុប្បន្នភាពសិទ្ធិ
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
            'label_kh' => 'required',
        ]);

        DB::transaction(function () use ($request, $role) {
            $role->update([
                'name' => $request->name,
                'label_kh' => $request->label_kh,
            ]);

            // sync() នឹងលុបសិទ្ធិចាស់ចោល ហើយថែមសិទ្ធិថ្មីដែល Admin ទើបតែគ្រីស
            $role->permissions()->sync($request->permissions ?? []);
        });

        return redirect()->route('roles.index')->with('success', 'កែសម្រួលតួនាទីដោយជោគជ័យ');
    }

    // ៦. លុប Role (វានឹងលុបការភ្ជាប់ក្នុងតារាង Pivot ដោយស្វ័យប្រវត្តិតាមរយៈ Cascade)
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'តួនាទីត្រូវបានលុបចេញពីប្រព័ន្ធ!');
    }

    public function show($id)
    {
        // ទាញយក Role ជាមួយ Permissions ដែលជាប់ជាមួយវា
        $role = Role::with('permissions')->findOrFail($id);

        // បើជាការហៅតាម AJAX ឱ្យ Return ជា JSON
        if (request()->ajax()) {
            return response()->json($role);
        }

        return view('role.show', compact('role'));
    }
}
