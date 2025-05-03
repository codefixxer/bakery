<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $userId = 1; // or dynamically fetch a superadmin if needed

        DB::table('clients')->insert([
            [
                'user_id'    => $userId,
                'name'       => 'Acme Corporation',
                'location'   => 'New York, NY',
                'phone'      => '212-555-1234',
                'email'      => 'contact@acme.com',
                'notes'      => 'Top‐tier client with recurring orders.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id'    => $userId,
                'name'       => 'Globex LLC',
                'location'   => 'Los Angeles, CA',
                'phone'      => '310-555-5678',
                'email'      => 'sales@globex.com',
                'notes'      => 'Prefers weekly bulk shipments.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id'    => $userId,
                'name'       => 'Initech',
                'location'   => 'Dallas, TX',
                'phone'      => '214-555-9012',
                'email'      => 'support@initech.com',
                'notes'      => 'Occasional one‑off orders.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id'    => $userId,
                'name'       => 'Umbrella Corp',
                'location'   => 'Chicago, IL',
                'phone'      => '312-555-3456',
                'email'      => 'info@umbrella.com',
                'notes'      => 'High‐volume seasonal orders.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'user_id'    => $userId,
                'name'       => 'Stark Industries',
                'location'   => 'Miami, FL',
                'phone'      => '305-555-7890',
                'email'      => 'tony@starkindustries.com',
                'notes'      => 'VIP client, negotiate pricing carefully.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
