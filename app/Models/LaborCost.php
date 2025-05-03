<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class LaborCost extends Model
{
    protected $fillable = [
        'num_chefs',
        'opening_days',
        'hours_per_day',
        'electricity',
        'ingredients',
        'leasing_loan',
        'packaging',
        'owner',
        'van_rental',
        'chefs',
        'shop_assistants',
        'other_salaries',
        'taxes',
        'other_categories',
        'driver_salary',
        'monthly_bep',
        'daily_bep',
        'shop_cost_per_min',
        'external_cost_per_min',
        'user_id', // ✅ add this for user ownership
    ];

    // ✅ Relationship: LaborCost belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
