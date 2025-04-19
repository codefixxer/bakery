<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    // We’ll allow mass assignment on these fields
    protected $fillable = ['ingredient_name', 'price_per_kg'];

    /**
     * Get all of the IngredientRecipe records that reference this ingredient.
     */
    public function ingredientRecipes()
    {
        return $this->hasMany(RecipeIngredient::class);
    }
}
