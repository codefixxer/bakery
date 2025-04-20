<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'ingredients_total_cost',
        'total_expense',
        'potential_margin', // <-- allow mass‑assignment
        'labor_cost_mode',
    ];

    /**
     * A recipe has many recipe_ingredient lines.
     */
    public function ingredients()
    {
        return $this->hasMany(RecipeIngredient::class);
    }
    public function category()
{
    return $this->belongsTo(RecipeCategory::class,'recipe_category_id');
}


public function department()       
{
    return $this->belongsTo(Department::class);
}
}
