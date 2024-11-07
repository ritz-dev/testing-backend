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
        Schema::create('barber', function (Blueprint $table) {
            $table->id();
            $table->string('uniqueid', 255)->index();
            $table->string('barber_name');
            $table->string('barber_photo')->nullable();
            $table->string('email');
            $table->string('contact_number');
            $table->integer('base_salary')->nullable();
            $table->string('working_days');
            $table->string('shop_id');
            $table->integer('commission_rate');
            $table->string('join_date');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barber');
    }
};
