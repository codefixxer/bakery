<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cost_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->nullable();
            $table->string('name'); // e.g. Utilities, Rent, Packaging
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cost_categories');
    }
};
