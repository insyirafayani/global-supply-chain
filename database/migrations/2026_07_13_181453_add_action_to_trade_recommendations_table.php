<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        if (!Schema::hasColumn('trade_recommendations', 'action')) {
            Schema::table('trade_recommendations', function (Blueprint $table) {
                $table->text('action')
                      ->nullable()
                      ->after('reason');
            });
        }

    }



    public function down(): void
    {

        if (Schema::hasColumn('trade_recommendations', 'action')) {
            Schema::table('trade_recommendations', function (Blueprint $table) {
                $table->dropColumn('action');
            });
        }

    }

};