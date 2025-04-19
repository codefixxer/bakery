<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'date',
    ];

    /**
     * Cast the `date` attribute to a Carbon instance
     */
    protected $casts = [
        'date' => 'date',    // or 'datetime' if you prefer
    ];
}
