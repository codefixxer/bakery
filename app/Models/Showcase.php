<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showcase extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'showcase_date',
        'template_action',
        'break_even',
        'total_revenue',
        'plus',
        'real_margin',
        'potential_income_average',
    ];

    /**
     * Get the recipe items for this showcase.
     */
    public function recipes()
    {
        return $this->hasMany(ShowcaseRecipe::class);
    }

    /**
     * (Optional) Relation to Department if you have a Department model.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
