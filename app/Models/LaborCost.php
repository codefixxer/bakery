<?php
// app/Models/LaborCost.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaborCost extends Model
{
    protected $fillable = [
      'num_chefs','opening_days','hours_per_day',
      'electricity','ingredients','leasing_loan','packaging','owner',
      'van_rental','chefs','shop_assistants','other_salaries',
      'taxes','other_categories','driver_salary',
      'monthly_bep','daily_bep','shop_cost_per_min','external_cost_per_min',
    ];
}
