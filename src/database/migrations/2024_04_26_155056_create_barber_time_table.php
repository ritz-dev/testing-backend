<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('barber_time', function (Blueprint $table) {
            $table->id();
            $table->string('barber_id');
            $table->string('working_day');
            $table->time('from');
            $table->time('to');
            $table->timestamps();
            $table->foreign('barber_id')->references('uniqueid')->on('barber')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barber_time');
    }
};
