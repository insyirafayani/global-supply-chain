<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{


    public function up(): void
    {

        Schema::table('risk_scores', function (Blueprint $table) {


            $table->enum(
                'risk_level',
                [
                    'Low Risk',
                    'Medium Risk',
                    'High Risk',
                    'Critical Risk'
                ]
            )
            ->change();


        });


    }




    public function down(): void
    {

        Schema::table('risk_scores', function (Blueprint $table) {


            $table->enum(
                'risk_level',
                [
                    'Low',
                    'Medium',
                    'High'
                ]
            )
            ->change();


        });


    }


};