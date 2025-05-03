<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ✅ Ensure superadmin user exists
        $superadmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // secure in real apps
            ]
        );

        DB::table('equipment')->insert([
            [
                'name' => 'Oven - Large Capacity',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Mixer - Dough Pro 5000',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Refrigerator - Industrial',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Convection Oven - Medium',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Pastry Sheeter',
                'user_id' => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
