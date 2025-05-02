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
        // clear the permission cache (so new roles/permissions take effect immediately)
        Artisan::call('permission:cache-reset');

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

        // 3) Fetch them back as a collection:
        $perms = Permission::whereIn('name', $allPermissions)->get();

        // 4) SUPER (super-admin) gets *all* permissions:
        $super = Role::firstOrCreate(['name' => 'super', 'guard_name' => 'web']);
        $super->syncPermissions($perms);

        // 5) ADMIN (all except “news”)
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

        // only give admin all permissions **except** `news`
        $adminPerms = $perms->where('name', '!=', 'news');
        $admin->syncPermissions($adminPerms);

        // 6) SHOP: only “showcase”
        $shop = Role::firstOrCreate(['name' => 'shop', 'guard_name' => 'web']);
        $shop->syncPermissions(['showcase']);

        // 7) LAB:
        $lab = Role::firstOrCreate(['name' => 'lab', 'guard_name' => 'web']);
        $lab->syncPermissions([
            'recipe',
            'ingredients',
            'production',
            'showcase',
            'external supplies',
        ]);

        // 8) MASTER (everything except sale comparison, costs, income)
        $master = Role::firstOrCreate(['name' => 'master', 'guard_name' => 'web']);
        $master->syncPermissions(
            $perms->reject(fn($p) => in_array($p->name, ['sale comparison', 'costs', 'income','news' ,'manage-user']))
        );
    }
}
