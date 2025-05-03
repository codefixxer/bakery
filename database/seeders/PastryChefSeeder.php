<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class PastryChefSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // ✅ Ensure superadmin exists
        $superadmin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // secure in production
            ]
        );

        DB::table('pastry_chefs')->insert([
            [
                'name'       => 'Chef Antonio Russo',
                'email'      => 'antonio@bakery.com',
                'phone'      => '0300-1112221',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Chef Amira Khan',
                'email'      => 'amira@bakery.com',
                'phone'      => '0312-5566778',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Chef Olivier Dupont',
                'email'      => 'olivier@bakery.com',
                'phone'      => '0321-9988776',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Chef Zara Sheikh',
                'email'      => 'zara@bakery.com',
                'phone'      => '0345-6655443',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Chef Hamza Farooq',
                'email'      => 'hamza@bakery.com',
                'phone'      => '0333-8899001',
                'user_id'    => $superadmin->id,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
