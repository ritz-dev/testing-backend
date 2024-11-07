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
        Schema::table('booking', function (Blueprint $table) {
            $table->integer('discount')->nullable();
            $table->enum('discount_type',['barber','shop','none']);
        });

        Schema::table('walk_in_customers', function(Blueprint $table) {
            $table->integer('discount')->nullable();
            $table->enum('discount_type',['none','barber','shop']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
