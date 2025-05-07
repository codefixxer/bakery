<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'is_active'];

    // Relationship to Notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}