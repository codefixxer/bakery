<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalSupplyRecipe extends Model
{
    protected $fillable = [
        'external_supply_id',
        'recipe_id',
        'category',
        'price',
        'qty',
        'total_amount',
    ];

    public function externalSupply()
    {
        return $this->belongsTo(ExternalSupply::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
