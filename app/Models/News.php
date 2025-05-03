<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'event_date',
        'is_active',
        'user_id', // ✅ Add this to support user ownership
    ];

    // ✅ Relationship: News belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
