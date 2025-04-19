<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('departments')->insert([
            [
                'name' => 'Production',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Packaging',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sales',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Logistics',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Quality Control',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
