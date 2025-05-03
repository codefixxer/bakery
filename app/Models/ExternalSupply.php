<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\User;
use App\Models\ExternalSupplyRecipe;
use App\Models\ReturnedGood;

class ExternalSupply extends Model
{
    protected $fillable = [
        'client_id',
        'supply_name',
        'supply_date',
        'save_template',
        'total_amount',
        'user_id',
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

    public function returnedGoods()
    {
        return $this->hasMany(ReturnedGood::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Alias for the recipes() relation, so your `lines` calls work.
     */
    public function lines()
    {
        return $this->hasMany(ExternalSupplyRecipe::class, 'external_supply_id');
    }
}
