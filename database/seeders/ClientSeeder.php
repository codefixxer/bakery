<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('clients')->insert([
            [
                'name'       => 'Acme Corporation',
                'location'   => 'New York, NY',
                'phone'      => '212-555-1234',
                'email'      => 'contact@acme.com',
                'notes'      => 'Top‐tier client with recurring orders.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Globex LLC',
                'location'   => 'Los Angeles, CA',
                'phone'      => '310-555-5678',
                'email'      => 'sales@globex.com',
                'notes'      => 'Prefers weekly bulk shipments.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Initech',
                'location'   => 'Dallas, TX',
                'phone'      => '214-555-9012',
                'email'      => 'support@initech.com',
                'notes'      => 'Occasional one‑off orders.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name'       => 'Umbrella Corp',
                'location'   => 'Chicago, IL',
                'phone'      => '312-555-3456',
                'email'      => 'info@umbrella.com',
                'notes'      => 'High‐volume seasonal orders.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
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
