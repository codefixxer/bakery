<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ShowcaseSeeder extends Seeder
{
    public function run(): void
    {
        $dept1 = DB::table('departments')->first()->id ?? null;
        $dept2 = DB::table('departments')->skip(1)->first()->id ?? null;

        if (!$dept1 || !$dept2) return;

        DB::table('showcases')->insert([
            [
                'department_id' => $dept1,
                'showcase_date' => Carbon::now()->subDays(1)->format('Y-m-d'),
                'template_action' => 'daily',
                'break_even' => 500.00,
                'total_revenue' => 600.00,
                'plus' => 100.00,
                'real_margin' => 15.5,
                'potential_income_average' => 650.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'department_id' => $dept2,
                'showcase_date' => Carbon::now()->format('Y-m-d'),
                'template_action' => 'none',
                'break_even' => 400.00,
                'total_revenue' => 420.00,
                'plus' => 20.00,
                'real_margin' => 10.0,
                'potential_income_average' => 450.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
