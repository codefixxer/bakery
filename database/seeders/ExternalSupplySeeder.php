<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExternalSupplySeeder extends Seeder
{
    public function run(): void
    {
        $client1 = DB::table('clients')->first()->id ?? null;
        $client2 = DB::table('clients')->skip(1)->first()->id ?? null;

        if (!$client1 || !$client2) return;

        DB::table('external_supplies')->insert([
            [
                'client_id' => $client1,
                'supply_date' => Carbon::now()->subDays(2)->format('Y-m-d'),
                'total_amount' => 280.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'client_id' => $client2,
                'supply_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'total_amount' => 370.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
