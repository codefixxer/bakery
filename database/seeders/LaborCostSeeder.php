<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LaborCost;

class LaborCostSeeder extends Seeder  // 👈 Must match the filename exactly
{
    public function run(): void
    {
        LaborCost::create([
            'num_chefs'             => 5,
            'opening_days'          => 22,
            'hours_per_day'         => 8,
            'electricity'           => 1500,
            'ingredients'           => 3000,
            'leasing_loan'          => 1200,
            'packaging'             => 800,
            'owner'                 => 2000,
            'van_rental'            => 500,
            'chefs'                 => 2500,
            'shop_assistants'       => 1000,
            'other_salaries'        => 600,
            'taxes'                 => 300,
            'other_categories'      => 150,
            'driver_salary'         => 400,
            'monthly_bep'           => 14000,
            'daily_bep'             => 636.36,
            'shop_cost_per_min'     => 0.0125,
            'external_cost_per_min' => 0.0113,
        ]);
    }
}
