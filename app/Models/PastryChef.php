<?php
// app/Models/PastryChef.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PastryChef extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone'];
}
