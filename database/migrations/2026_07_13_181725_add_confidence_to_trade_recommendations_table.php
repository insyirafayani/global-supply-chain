<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::table('trade_recommendations', function (Blueprint $table) {


            $table->decimal(
                'confidence',
                5,
                2
            )
            ->default(0)
            ->after('action');


        });

    }



    public function down(): void
    {

        Schema::table('trade_recommendations', function (Blueprint $table) {


            $table->dropColumn('confidence');


        });

    }

};