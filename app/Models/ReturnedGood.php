<?php

// app/Models/ReturnedGood.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturnedGood extends Model
{
    protected $fillable = [
        'external_supply_id',  // ← add this

        'client_id',
        'return_date',
        'total_amount',
    ];

    protected $casts = [
        'return_date' => 'date',
    ];

    // Relationship with Client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relationship with ExternalSupply (each return is linked to one supply)
    public function externalSupply()
    {
        return $this->belongsTo(ExternalSupply::class, 'external_supply_id');  // Assuming 'external_supply_id' is the foreign key
    }

    // Relationship with ReturnedGoodRecipe (optional, depending on your needs)
    public function recipes()
    {
        return $this->hasMany(ReturnedGoodRecipe::class);
    }

    public function lines()
    {
        return $this->hasMany(ReturnedGoodRecipe::class);
    }
}
