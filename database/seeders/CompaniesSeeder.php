<?php
// database/seeders/CompaniesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompaniesSeeder extends Seeder
{
    public function run()
    {
        Company::firstOrCreate(['id' => 1], ['name' => 'SuperAdmin']);
        Company::firstOrCreate(['id' => 2], ['name' => 'Acme Bakery']);
    }
}
