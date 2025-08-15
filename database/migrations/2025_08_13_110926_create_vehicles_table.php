<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->integer('vehicle_id')->unique();
            $table->foreignId('category_id')->nullable()->constrained('vehicles_categories')->onDelete('cascade');
            $table->foreignId('town_id')->nullable()->constrained('towns')->onDelete('cascade');
            $table->boolean('condition')->default(false);
            $table->boolean('Status')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
