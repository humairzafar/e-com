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
        Schema::table('designation', function (Blueprint $table) {
             $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade')->after('is_head_office_deparment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('designation', function (Blueprint $table) {
            //
        });
    }
};
