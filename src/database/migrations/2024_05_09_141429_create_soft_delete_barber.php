<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('barber', function(Blueprint $table)
        {
            $table->softDeletes();
        });
    }
  
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('barber', function(Blueprint $table)
        {
            $table->dropSoftDeletes();
        });
    }
};
