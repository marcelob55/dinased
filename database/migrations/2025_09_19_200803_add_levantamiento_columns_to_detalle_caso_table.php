<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detalle_caso', function (Blueprint $table) {
            // agrega columnas si no existen
            if (!Schema::hasColumn('detalle_caso', 'fecha_levantamiento')) {
                $table->date('fecha_levantamiento')->nullable()->after('fecha_hecho');
            }
            if (!Schema::hasColumn('detalle_caso', 'hora_levantamiento')) {
                $table->time('hora_levantamiento')->nullable()->after('hora_hecho');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detalle_caso', function (Blueprint $table) {
            if (Schema::hasColumn('detalle_caso', 'hora_levantamiento')) {
                $table->dropColumn('hora_levantamiento');
            }
            if (Schema::hasColumn('detalle_caso', 'fecha_levantamiento')) {
                $table->dropColumn('fecha_levantamiento');
            }
        });
    }
};
