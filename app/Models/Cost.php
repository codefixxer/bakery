<?php

namespace App\Models;

use App\Models\CostCategory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    protected $fillable = [
        'supplier','amount','due_date',
        'category_id','other_category'
      ];
    public function category()
    {
        return $this->belongsTo(CostCategory::class);
    }

    
}
