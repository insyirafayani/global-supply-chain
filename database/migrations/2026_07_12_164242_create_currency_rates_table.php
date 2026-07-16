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
    Schema::create('currency_rates', function (Blueprint $table) {

        $table->id();


        $table->foreignId('country_id')
            ->nullable()
            ->constrained()
            ->nullOnDelete();


        $table->string('base_currency')
              ->default('USD');


        $table->string('currency_code');


        $table->decimal('exchange_rate',12,6)
              ->nullable();


        $table->decimal('previous_rate',12,6)
              ->nullable();


        $table->decimal('change_percent',8,3)
              ->nullable();


        $table->string('currency_status')
              ->nullable();


        $table->timestamp('recorded_at')
              ->nullable();


        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
