<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Department;
use App\Models\RecipeCategory;
use App\Models\RecipeIngredient;

        class Recipe extends Model
        {
            use HasFactory;

            protected $fillable = [
                'recipe_name',
                'recipe_category_id',
                'department_id',
                'sell_mode',
                'selling_price_per_piece',
                'selling_price_per_kg',
                'labour_time_min',
                'labour_cost',
                'packing_cost',
                'labor_cost_mode',
                'ingredients_total_cost',
                'total_expense',
                'potential_margin',
                'total_pieces',
                'recipe_weight',
                'production_cost_per_kg',
            ];

            public function ingredients()
            {
                return $this->hasMany(RecipeIngredient::class);
            }

            public function category()
            {
                return $this->belongsTo(RecipeCategory::class, 'recipe_category_id');
            }

            public function department()
            {
                return $this->belongsTo(Department::class);
            }




            // in App\Models\Recipe.php

/**
 * Get the total € cost of all ingredients for one “batch” of this recipe.
 */
public function getIngredientsCostPerBatchAttribute(): float
{
    // sum up each ingredient’s cost:
    return $this->ingredients->sum(function($ri) {
        // price_per_kg is €/kg, quantity_g is grams
        return ($ri->quantity_g / 1000) * $ri->ingredient->price_per_kg;
    });
}

/**
 * Get the cost-per‐unit that we need in the JS.
 */
public function getRawCostPerUnitAttribute(): float
{
    if ($this->sell_mode === 'kg') {
        // batch weight is recipe_weight (in kg)
        // cost_per_batch ÷ recipe_weight = €/kg
        return $this->ingredients_cost_per_batch / ($this->recipe_weight ?: 1);
    }

    // else sell_mode is “piece”, and total_pieces tells how many pieces per batch
    return $this->ingredients_cost_per_batch / ($this->total_pieces ?: 1);
}

        }
