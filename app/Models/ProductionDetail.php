<?php
// app/Models/ProductionDetail.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'production_id',
        'recipe_id',
        'pastry_chef_id',
        'quantity',
        'execution_time',
        'equipment_ids',
        'potential_revenue',
    ];

    // Cast equipment_ids from JSON to array automatically
    protected $casts = [
        'equipment_ids' => 'array',
    ];

    public function production()
    {
        return $this->belongsTo(Production::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    public function chef()
    {
        return $this->belongsTo(PastryChef::class, 'pastry_chef_id');
    }
}
