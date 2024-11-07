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
        Schema::table('walk_in_customers', function (Blueprint $table) {
            $table->string('name')->default('')->after('status');
            $table->string('phone')->default('');
        });

        DB::table('walk_in_customers')->update(['name' => 'General Customer']);

        Schema::table('walk_in_customers', function (Blueprint $table) {
            $table->string('name')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('walk_in_customers', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('phone');
        });
    }
};
