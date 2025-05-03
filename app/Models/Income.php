<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'date',
        'user_id', // ✅ allow mass assignment
    ];

    protected $casts = [
        'date' => 'date', // ✅ cast for Carbon
    ];

    // ✅ Relationship: this income belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
