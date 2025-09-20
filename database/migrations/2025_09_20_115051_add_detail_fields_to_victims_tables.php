<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Devuelve el primer nombre de tabla que exista */
    private function firstExisting(array $candidates): ?string
    {
        foreach ($candidates as $name) {
            if (Schema::hasTable($name)) {
                return $name;
            }
        }
        return null;
    }

    public function up(): void
    {
        // Cambia estos arrays si tus tablas usan otros nombres
        $fallCandidates = ['fallecidos','victimas_fallecidas','occisos','caso_fallecidos'];
        $heriCandidates = ['heridos','victimas_heridos','lesionados','caso_heridos'];

        if ($fall = $this->firstExisting($fallCandidates)) {
            Schema::table($fall, function (Blueprint $t) use ($fall) {
                if (!Schema::hasColumn($fall,'antecedentes_det')) {
                    $t->string('antecedentes_det', 255)->nullable()->after('antecedentes');
                }
                if (Schema::hasColumn($fall,'sajte') && !Schema::hasColumn($fall,'sajte_det')) {
                    $t->string('sajte_det', 255)->nullable()->after('sajte');
                }
                if (Schema::hasColumn($fall,'noticia_fiscalia') && !Schema::hasColumn($fall,'noticia_fiscalia_det')) {
                    $t->string('noticia_fiscalia_det', 255)->nullable()->after('noticia_fiscalia');
                }
                if (Schema::hasColumn($fall,'gao') && !Schema::hasColumn($fall,'gao_det')) {
                    $t->string('gao_det', 255)->nullable()->after('gao');
                }
            });
        }

        if ($heri = $this->firstExisting($heriCandidates)) {
            Schema::table($heri, function (Blueprint $t) use ($heri) {
                if (!Schema::hasColumn($heri,'antecedentes_det')) {
                    $t->string('antecedentes_det', 255)->nullable()->after('antecedentes');
                }
                if (Schema::hasColumn($heri,'sajte') && !Schema::hasColumn($heri,'sajte_det')) {
                    $t->string('sajte_det', 255)->nullable()->after('sajte');
                }
                if (Schema::hasColumn($heri,'noticia_fiscalia') && !Schema::hasColumn($heri,'noticia_fiscalia_det')) {
                    $t->string('noticia_fiscalia_det', 255)->nullable()->after('noticia_fiscalia');
                }
                if (Schema::hasColumn($heri,'gao') && !Schema::hasColumn($heri,'gao_det')) {
                    $t->string('gao_det', 255)->nullable()->after('gao');
                }
            });
        }
    }

    public function down(): void
    {
        $groups = [
            ['fallecidos','victimas_fallecidas','occisos','caso_fallecidos'],
            ['heridos','victimas_heridos','lesionados','caso_heridos'],
        ];

        foreach ($groups as $candidates) {
            $tbl = $this->firstExisting($candidates);
            if (!$tbl) continue;

            Schema::table($tbl, function (Blueprint $t) use ($tbl) {
                foreach (['antecedentes_det','sajte_det','noticia_fiscalia_det','gao_det'] as $col) {
                    if (Schema::hasColumn($tbl,$col)) {
                        $t->dropColumn($col);
                    }
                }
            });
        }
    }
};
