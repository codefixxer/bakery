<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('labor_costs', function (Blueprint $table) {
            $table->id();
            $table->decimal('cost_per_minute', 10, 2)->default();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('labor_costs');
    }
};
