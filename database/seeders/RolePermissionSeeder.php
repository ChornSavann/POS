<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. បង្កើត Roles
        $admin = Role::updateOrCreate(['name' => 'admin'], ['label_kh' => 'អ្នកគ្រប់គ្រង']);
        $cashier = Role::updateOrCreate(['name' => 'cashier'], ['label_kh' => 'អ្នកកាន់លុយ']);

        // 2. បង្កើត Permissions ជាមួយលម្អិតពេញលេញ (មិនឲ្យបាត់បង់ទិន្នន័យ)
        $permissionsData = [
            'view_products' => ['label_kh' => 'មើលបញ្ជីទំនិញ', 'group_name' => 'Inventory'],
            'create_invoice' => ['label_kh' => 'បង្កើតវិក្កយបត្រ', 'group_name' => 'Sales'],
            'view_reports'  => ['label_kh' => 'មើលរបាយការណ៍', 'group_name' => 'Reports'],
            'make_sale'     => ['label_kh' => 'ធ្វើការលក់ទំនិញ', 'group_name' => 'Sales'], // បន្ថែមសម្រាប់ Cashier
        ];

        $permissions = [];
        foreach ($permissionsData as $name => $details) {
            $permissions[$name] = Permission::updateOrCreate(
                ['name' => $name],
                [
                    'label_kh' => $details['label_kh'],
                    'group_name' => $details['group_name']
                ]
            );
        }

        // 3. ផ្ដល់សិទ្ធិឱ្យ Role (Sync IDs)
        // Admin ទទួលបានសិទ្ធិទាំងអស់ដែលមាន
        $allPermissionIds = collect($permissions)->pluck('id')->toArray();
        $admin->permissions()->sync($allPermissionIds);

        // Cashier ទទួលបានតែសិទ្ធិលក់ និងមើលទំនិញ
        $cashierPermissions = [
            $permissions['make_sale']->id,
            $permissions['create_invoice']->id,
            $permissions['view_products']->id,
        ];
        $cashier->permissions()->sync($cashierPermissions);
    }
}
