<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('detalle_caso', function (Blueprint $t) {
            if (!Schema::hasColumn('detalle_caso','indicios_detalle')) {
                $t->text('indicios_detalle')->nullable()->after('indicios');
            }
        });
    }

    public function down(): void
    {
        Schema::table('detalle_caso', function (Blueprint $t) {
            if (Schema::hasColumn('detalle_caso','indicios_detalle')) {
                $t->dropColumn('indicios_detalle');
            }
        });
    }
};
