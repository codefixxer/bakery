<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShowcaseRecipe extends Model
{
    use HasFactory;

    protected $casts = [
        'reuse' => 'integer',
        'waste' => 'integer',
    ];

    protected $fillable = [
        'showcase_id',
        'recipe_id',
        'category',
        'price',
        'quantity',
        'sold',
        'reuse',
        'waste',
        'potential_income',
        'actual_revenue',
    ];

    /**
     * Get the showcase that owns this recipe item.
     */
    public function showcase()
    {
        return $this->belongsTo(Showcase::class);
    }

    /**
     * (Optional) Relation to Recipe if you have a Recipe model.
     */
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
