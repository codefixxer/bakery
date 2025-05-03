<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class CostCategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ✅ Ensure the user exists
        $superadmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // use secure password in production
            ]
        );

        DB::table('cost_categories')->insert([
            [
                'name'       => 'Utilities',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Rent',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Packaging',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Raw Materials',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Salaries',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
