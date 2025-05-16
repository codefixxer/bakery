<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Department;
use App\Models\RecipeCategory;
use App\Models\RecipeIngredient;
use App\Models\LaborCost;  // <— import your LaborCost model
use App\Models\User;

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
        'labor_cost_id',
        'labor_cost_mode',
        'packing_cost',
        'total_expense',
        'potential_margin',
        'total_pieces',
        'recipe_weight',
        'production_cost_per_kg',
        'add_as_ingredient',
        'user_id',
    ];

        public function getIngredientsTotalCostAttribute(): float
    {
        // uses the dynamic `cost` on each pivot
        return $this->ingredients->sum('cost');
    }



    public function laborCostRate()
    {
        return $this->belongsTo(LaborCost::class, 'labor_cost_id');
    }

    /**
     * Compute actual € labour cost on the fly.
     */
public function getLaborCostAttribute(): float
{
    $rate = $this->labor_cost_mode === 'external'
          ? ($this->laborCostRate->external_cost_per_min ?? 0)
          : ($this->laborCostRate->shop_cost_per_min     ?? 0);

    return round($this->labour_time_min * $rate, 2);
}

    // … all your other relationships & accessors remain unchanged …


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

    // ✅ Add user relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the total € cost of all ingredients for one “batch” of this recipe.
     */
    public function getIngredientsCostPerBatchAttribute(): float
    {
        return $this->ingredients->sum(function ($ri) {
            return ($ri->quantity_g / 1000) * $ri->ingredient->price_per_kg;
        });
    }

    /**
     * Get the cost-per‐unit that we need in the JS.
     */
    public function getRawCostPerUnitAttribute(): float
    {
        if ($this->sell_mode === 'kg') {
            return $this->ingredients_cost_per_batch / ($this->recipe_weight ?: 1);
        }

        return $this->ingredients_cost_per_batch / ($this->total_pieces ?: 1);
    }
}
