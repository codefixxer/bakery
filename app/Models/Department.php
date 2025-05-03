<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Recipe;
use App\Models\User;

class Department extends Model
{
    protected $fillable = [
        'name',
        'user_id', // ✅ Add user_id to mass assignment
    ];

    // ✅ Relationship: department has many recipes
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    // ✅ Relationship: department belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
