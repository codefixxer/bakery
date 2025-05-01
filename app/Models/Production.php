<?php
    // app/Models/Production.php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Factories\HasFactory;
    use Illuminate\Database\Eloquent\Model;

    class Production extends Model
    {
        use HasFactory;

        protected $fillable = [
            'production_name',
            'save_template',
            'production_date',
            'total_potential_revenue',
        ];

        public function details()
        {
            return $this->hasMany(ProductionDetail::class);
        }
    }
