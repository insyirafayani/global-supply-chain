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
        Schema::table('weather_data', function (Blueprint $table) {
            if (!Schema::hasColumn('weather_data', 'humidity')) {
                $table->decimal('humidity', 5, 2)->nullable()->after('wind_speed');
            }
            if (!Schema::hasColumn('weather_data', 'pressure')) {
                $table->decimal('pressure', 7, 2)->nullable()->after('humidity');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weather_data', function (Blueprint $table) {
            if (Schema::hasColumn('weather_data', 'humidity')) {
                $table->dropColumn('humidity');
            }
            if (Schema::hasColumn('weather_data', 'pressure')) {
                $table->dropColumn('pressure');
            }
        });
    }
};
