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
    Schema::create('countries', function (Blueprint $table) {

        $table->id();

        $table->string('name');

        $table->string('official_name')
              ->nullable();

        $table->string('iso2',2)
              ->nullable();

        $table->string('iso3',3)
              ->nullable();

        $table->string('capital')
              ->nullable();

        $table->string('region')
              ->nullable();

        $table->string('subregion')
              ->nullable();


        // currency

        $table->string('currency')
              ->nullable();

        $table->string('currency_code')
              ->nullable();


        // language

        $table->string('language')
              ->nullable();


        // map

        $table->decimal('latitude',10,7)
              ->nullable();

        $table->decimal('longitude',10,7)
              ->nullable();


        $table->text('flag')
              ->nullable();


        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
