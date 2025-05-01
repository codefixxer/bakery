<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Showcase extends Model
{
    protected $fillable = [
        'showcase_date','template_action',
        'break_even','total_revenue','plus','real_margin','potential_income_average'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function recipes()
    {
        return $this->hasMany(ShowcaseRecipe::class);
    }


    protected $casts = [
        'showcase_date' => 'date',    // ← this makes →$this->showcase_date a Carbon
    ];

}
