<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run() {
        $permissions = [
            ['name' => 'view_products', 'label_kh' => 'មើលបញ្ជីទំនិញ', 'group_name' => 'Inventory'],
            ['name' => 'create_invoice', 'label_kh' => 'បង្កើតវិក្កយបត្រ', 'group_name' => 'Sales'],
            ['name' => 'view_reports', 'label_kh' => 'មើលរបាយការណ៍', 'group_name' => 'Reports'],
        ];

        foreach ($permissions as $p) {
            \App\Models\Permission::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
