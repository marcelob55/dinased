<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('detalle_caso', function (Blueprint $table) {
            // Usa JSON si tu MySQL lo soporta, si no, cambia a TEXT
            if (Schema::hasColumn('detalle_caso','entrevistas') === false) {
                $table->json('entrevistas')->nullable()->after('circunstancias');
            }
            if (Schema::hasColumn('detalle_caso','actividades') === false) {
                $table->json('actividades')->nullable()->after('entrevistas');
            }
            if (Schema::hasColumn('detalle_caso','fecha_hecho') === false) {
                $table->date('fecha_hecho')->nullable()->after('lugar_hecho');
            }
            if (Schema::hasColumn('detalle_caso','hora_hecho') === false) {
                $table->time('hora_hecho')->nullable()->after('fecha_hecho');
            }
        });
    }

    public function down(): void {
        Schema::table('detalle_caso', function (Blueprint $table) {
            if (Schema::hasColumn('detalle_caso','actividades')) $table->dropColumn('actividades');
            if (Schema::hasColumn('detalle_caso','entrevistas')) $table->dropColumn('entrevistas');
            if (Schema::hasColumn('detalle_caso','hora_hecho')) $table->dropColumn('hora_hecho');
            if (Schema::hasColumn('detalle_caso','fecha_hecho')) $table->dropColumn('fecha_hecho');
        });
    }
};

