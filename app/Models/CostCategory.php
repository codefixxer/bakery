<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CostCategory extends Model
{
    protected $fillable = ['name'];


    public function costs()
    {
        return $this->hasMany(Cost::class);
    }
}

