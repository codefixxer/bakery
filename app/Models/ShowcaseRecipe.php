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
    protected $table = 'showcase_recipes';
    protected $fillable = [
        'showcase_id','recipe_id','category','price',
        'quantity','sold','reuse','waste','potential_income','actual_revenue'
    ];

    public function showcase()
    {
        return $this->belongsTo(Showcase::class);
    }
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
