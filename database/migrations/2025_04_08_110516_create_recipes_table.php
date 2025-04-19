<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();
            $table->string('recipe_name');
        
            $table->foreignId('recipe_category_id')
                  ->constrained('recipe_categories')
                  ->cascadeOnDelete();
        
            $table->foreignId('department_id')                // ↓ FK builds now
                  ->constrained('departments')
                  ->cascadeOnDelete();
        
            $table->enum('sell_mode', ['piece','kg'])->default('kg');
            $table->decimal('selling_price_per_piece', 10, 2)->nullable();
            $table->decimal('selling_price_per_kg',    10, 2)->nullable();
            $table->integer('labour_time_min')->default(0);
            $table->decimal('labour_cost',     12, 2)->default(0);
            $table->decimal('packing_cost',     8, 2)->default(0);
        
            $table->decimal('ingredients_total_cost', 12, 2)->default(0);
            $table->decimal('total_expense',         12, 2)->default(0);
            $table->decimal('potential_margin',      12, 2)->default(0);
        
            $table->timestamps();
        });
        


        Schema::create('recipe_ingredient', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recipe_id')
                  ->constrained('recipes')
                  ->onDelete('cascade');
            $table->foreignId('ingredient_id')
                  ->constrained('ingredients');
            $table->integer('quantity_g')->default(0);
            $table->decimal('cost', 12, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
        Schema::dropIfExists('recipe_ingredient ');
    }
};
