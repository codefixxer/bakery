<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        
            'name',
            'email',
            'password',
            // Add this 👇
            'created_by',
        ];
        

    /**
     * The attributes that should be hidden for arrays (and JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'status'            => 'boolean',
    ];

    public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

}
