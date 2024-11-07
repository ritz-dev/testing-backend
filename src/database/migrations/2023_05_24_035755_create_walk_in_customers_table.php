<?php

use Illuminate\Support\Facades\DB;
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
        Schema::create('walk_in_customers', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueid');
            $table->string('barber_id');
            $table->foreign('barber_id')->references('uniqueid')->on('barber')->onDelete('cascade');
            $table->string('service_id');
            $table->foreign('service_id')->references('uniqueid')->on('service')->onDelete('cascade');
            $table->date('date');
            $table->string('time_period_id');
            $table->enum('status',['active','cancel', 'confirm', 'complete'])->default('complete');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('walk_in_customers');
    }
};
