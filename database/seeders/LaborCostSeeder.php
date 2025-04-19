<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaborCostSeeder extends Seeder
{
    public function run()
    {
        DB::table('labor_costs')->insert([
            'cost_per_minute' => 5.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
