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
        Schema::create('booking', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueid');
            $table->string('customer_id')->nullable();
            $table->foreign('customer_id')->references('uniqueid')->on('customer')->onDelete('cascade');
            $table->string('barber_id', 255);
            $table->foreign('barber_id')->references('uniqueid')->on('barber')->onDelete('cascade');
            $table->string('service_id');
            $table->foreign('service_id')->references('uniqueid')->on('service')->onDelete('cascade');
            $table->date('date');
            $table->string('time_period_id');
            $table->longText('note')->nullable();
            $table->enum('status',['active','cancel', 'confirm', 'complete']);
            $table->enum('type', ['by_phone', 'by_customer'])->default('by_customer');
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking');
    }
};
