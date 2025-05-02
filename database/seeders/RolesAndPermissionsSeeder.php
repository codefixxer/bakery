<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1) Define all the permissions your app uses:
        $allPermissions = [
            'manage-users',
            'view users',
            'view roles',
            'view permissions',
            'ingredients',
            'sale comparison',
            'recipe',
            'external supplies',
            'returned goods',
            'recipe categories',
            'clients',
            'cost categories',
            'departments',
            'pastry chefs',
            'equipment',
            'showcase',
            'costs',
            'income',
            'cost comparison',
            'news',
            'production',
            'labor cost',
        ];

        // 2) Create / update each permission
        foreach ($allPermissions as $perm) {
            Permission::firstOrCreate([
                'name'       => $perm,
                'guard_name' => 'web',
            ]);
        }

        // 3) Fetch them back as a collection for easy slicing:
        $perms = Permission::whereIn('name', $allPermissions)->get()->keyBy('name');

        // 4) Create ADMIN (all except "news")
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $adminPerms = $perms->except('news')->values();
        $admin->syncPermissions($adminPerms);

        // 5) SHOP: only "showcase"
// SHOP
$shop = Role::firstOrCreate(['name'=>'shop','guard_name'=>'web']);
$shop->syncPermissions(['showcase']);

// LAB
$lab = Role::firstOrCreate(['name'=>'lab','guard_name'=>'web']);
$lab->syncPermissions([
    'recipe',
    'ingredients',
    'production',
    'showcase',
    'external supplies',
]);

        // 7) MASTER: everything except sale comparison, costs, income
        $master = Role::firstOrCreate(['name' => 'master', 'guard_name' => 'web']);
        $masterPerms = $perms
            ->except(['sale comparison', 'costs', 'income'])
            ->values();
        $master->syncPermissions($masterPerms);
    }
}
