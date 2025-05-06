<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // 1) Clear Spatie’s cache so new roles/permissions take effect immediately
        Artisan::call('permission:cache-reset');

        // 2) Define all the permissions your app uses:
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
            'can add admin',
        ];

        // 3) Create / update each permission
        foreach ($allPermissions as $perm) {
            Permission::firstOrCreate([
                'name'       => $perm,
                'guard_name' => 'web',
            ]);
        }

        // 4) Fetch them back as a collection
        $perms = Permission::whereIn('name', $allPermissions)->get();

        // 5) SUPER‑ADMIN gets *all* permissions
        $super = Role::firstOrCreate(
            ['name' => 'super-admin', 'guard_name' => 'web']
        );
        $super->syncPermissions($perms);

        // 6) ADMIN: everything except news, view roles, view permissions
        $admin = Role::firstOrCreate(
            ['name' => 'admin', 'guard_name' => 'web']
        );
        $adminPerms = $perms->reject(fn($p) =>
            in_array($p->name, ['news', 'view roles', 'view permissions'])
        );
        $admin->syncPermissions($adminPerms);

        // 7) SHOP: only “showcase”
        $shop = Role::firstOrCreate(
            ['name' => 'shop', 'guard_name' => 'web']
        );
        $shop->syncPermissions(['showcase']);

        // 8) LAB: recipe, ingredients, production, showcase, external supplies
        $lab = Role::firstOrCreate(
            ['name' => 'lab', 'guard_name' => 'web']
        );
        $lab->syncPermissions([
            'recipe',
            'ingredients',
            'production',
            'showcase',
            'external supplies',
        ]);

        // 9) MASTER: everything except sale comparison, costs, income, news, manage-users, view roles, view permissions
        $master = Role::firstOrCreate(
            ['name' => 'master', 'guard_name' => 'web']
        );
        $master->syncPermissions(
            $perms->reject(fn($p) =>
                in_array($p->name, [
                    'sale comparison',
                    'costs',
                    'income',
                    'news',
                    'manage-users',
                    'view roles',
                    'view permissions',
                ])
            )
        );
    }
}
