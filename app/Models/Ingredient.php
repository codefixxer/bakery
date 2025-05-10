<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\RecipeIngredient;
use App\Models\User;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'ingredient_name',
        'price_per_kg',
        'user_id', // ✅ allow mass assignment of user_id
    ];

    /**
     * Get all of the RecipeIngredient records that reference this ingredient.
     */
    public function ingredientRecipes()
    {
        return $this->hasMany(RecipeIngredient::class);
    }

    // ✅ Ingredient belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recipes()
{
    return $this->belongsToMany(Recipe::class, 'recipe_ingredient')
                ->withPivot('quantity_g','cost'); // etc
}
}
