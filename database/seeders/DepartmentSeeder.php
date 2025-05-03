<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ✅ Ensure superadmin exists
        $superadmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        DB::table('departments')->insert([
            [
                'name' => 'Production',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Packaging',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sales',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Logistics',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Quality Control',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
