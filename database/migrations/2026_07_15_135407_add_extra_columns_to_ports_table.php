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
            if (!Schema::hasColumn('ports', 'status')) {
                $table->string('status')->nullable()->after('location');
            }
            if (!Schema::hasColumn('ports', 'trade_volume')) {
                $table->bigInteger('trade_volume')->nullable()->after('status');
            }
            if (!Schema::hasColumn('ports', 'terminal')) {
                $table->integer('terminal')->nullable()->after('trade_volume');
            }
            if (!Schema::hasColumn('ports', 'capacity')) {
                $table->bigInteger('capacity')->nullable()->after('terminal');
            }
            if (!Schema::hasColumn('ports', 'congestion')) {
                $table->string('congestion')->nullable()->after('capacity');
            }
            if (!Schema::hasColumn('ports', 'port_type')) {
                $table->string('port_type')->nullable()->after('congestion');
            }
            if (!Schema::hasColumn('ports', 'risk')) {
                $table->string('risk')->nullable()->after('port_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ports', function (Blueprint $table) {
            $cols = ['status', 'trade_volume', 'terminal', 'capacity', 'congestion', 'port_type', 'risk'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('ports', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
