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
        Schema::table('ports', function (Blueprint $table) {
            $table->string('status')->nullable()->after('location');
            $table->bigInteger('trade_volume')->nullable()->after('status');
            $table->integer('terminal')->nullable()->after('trade_volume');
            $table->bigInteger('capacity')->nullable()->after('terminal');
            $table->string('congestion')->nullable()->after('capacity');
            $table->string('port_type')->nullable()->after('congestion');
            $table->string('risk')->nullable()->after('port_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ports', function (Blueprint $table) {
            $table->dropColumn([
                'status',
                'trade_volume',
                'terminal',
                'capacity',
                'congestion',
                'port_type',
                'risk'
            ]);
        });
    }
};
