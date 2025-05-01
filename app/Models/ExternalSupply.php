<?php
// app/Models/ExternalSupply.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalSupply extends Model
{
    protected $fillable = [
        'client_id',
        'supply_date',
        'total_amount',
    ];

    protected $casts = [
        'supply_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function recipes()
    {
        return $this->hasMany(ExternalSupplyRecipe::class);
    }

    // ← add this:
    public function returnedGoods()
    {
        return $this->hasMany(ReturnedGood::class);
    }
}
