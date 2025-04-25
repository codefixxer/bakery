<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'user_id', // assuming notifications are user-specific
        'is_read', // to keep track of read/unread status
        'is_new',  // to track new/unread notifications
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Assuming each notification belongs to a user
    }
}
