<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
{
    Schema::create('ingredients', function (Blueprint $table) {
        $table->id();
        $table->string('ingredient_name');
        $table->decimal('price_per_kg', 8, 2);
        $table->timestamps();
    });
    
}

    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
