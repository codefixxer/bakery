<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExternalSupply extends Model
{
    protected $fillable = [
        'client_id',
        'supply_date',
        'total_amount',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function recipes()
    {
        return $this->hasMany(ExternalSupplyRecipe::class);
    }
}
