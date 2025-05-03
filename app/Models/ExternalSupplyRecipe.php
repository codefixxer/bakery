<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ExternalSupply;
use App\Models\Recipe;
use App\Models\User;

class ExternalSupplyRecipe extends Model
{
    protected $fillable = [
        'external_supply_id',
        'recipe_id',
        'category',
        'price',
        'qty',
        'total_amount',
        'user_id', // ✅ Add user_id to fillable
    ];

    public function externalSupply()
    {
        return $this->belongsTo(ExternalSupply::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    // ✅ Relationship: this recipe belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
