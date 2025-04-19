<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PastryChefSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('pastry_chefs')->insert([
            [
                'name' => 'Chef Antonio Russo',
                'email' => 'antonio@bakery.com',
                'phone' => '0300-1112221',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Chef Amira Khan',
                'email' => 'amira@bakery.com',
                'phone' => '0312-5566778',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Chef Olivier Dupont',
                'email' => 'olivier@bakery.com',
                'phone' => '0321-9988776',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Chef Zara Sheikh',
                'email' => 'zara@bakery.com',
                'phone' => '0345-6655443',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Chef Hamza Farooq',
                'email' => 'hamza@bakery.com',
                'phone' => '0333-8899001',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
