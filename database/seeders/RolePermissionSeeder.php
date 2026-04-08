<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   // database/seeders/RolePermissionSeeder.php
    public function run()
    {
        // 1. បង្កើត Roles
        $admin = Role::updateOrCreate(['name' => 'admin'], ['label_kh' => 'អ្នកគ្រប់គ្រង']);
        $cashier = Role::updateOrCreate(['name' => 'cashier'], ['label_kh' => 'អ្នកកាន់លុយ']);

        // 2. បង្កើត Permissions
        $p1 = Permission::updateOrCreate(['name' => 'view_reports']);
        $p2 = Permission::updateOrCreate(['name' => 'make_sale']);

        // 3. ផ្ដល់សិទ្ធិឱ្យ Role
        $admin->permissions()->sync([$p1->id, $p2->id]); // Admin បានទាំងអស់
        $cashier->permissions()->sync([$p2->id]);        // Cashier បានតែលក់
    }
}
