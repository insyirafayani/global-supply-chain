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
        Schema::table('currency_rates', function (Blueprint $table) {
            $table->decimal('exchange_rate', 20, 6)->nullable()->change();
            $table->decimal('previous_rate', 20, 6)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('currency_rates', function (Blueprint $table) {
            $table->decimal('exchange_rate', 12, 6)->nullable()->change();
            $table->decimal('previous_rate', 12, 6)->nullable()->change();
        });
    }
};
