<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function ensureFallecidos(): void
    {
        if (!Schema::hasTable('fallecidos')) {
            Schema::create('fallecidos', function (Blueprint $t) {
                $t->id();
                // ajusta si tu PK de casos es otra
                $t->unsignedBigInteger('caso_id')->index();

                $t->string('etiqueta', 2)->nullable();
                $t->string('nombres', 120)->nullable();
                $t->string('apellidos', 120)->nullable();
                $t->string('cedula', 20)->nullable();
                $t->unsignedSmallInteger('edad')->nullable();
                $t->char('sexo', 1)->nullable();
                $t->string('observacion', 255)->nullable();

                $t->string('alias', 120)->nullable();
                $t->string('nacionalidad', 120)->nullable();
                $t->string('ocupacion', 120)->nullable();
                $t->string('movilizacion', 120)->nullable();

                $t->string('antecedentes', 10)->nullable();
                $t->string('antecedentes_det', 255)->nullable();

                $t->string('sajte', 10)->nullable();
                $t->string('sajte_det', 255)->nullable();

                $t->string('noticia_fiscalia', 10)->nullable();
                $t->string('noticia_fiscalia_det', 255)->nullable();

                $t->string('gao', 10)->nullable();
                $t->string('gao_det', 255)->nullable();

                $t->timestamps();
            });
        } else {
            Schema::table('fallecidos', function (Blueprint $t) {
                foreach ([
                    'antecedentes_det'      => 'antecedentes',
                    'sajte_det'             => 'sajte',
                    'noticia_fiscalia_det'  => 'noticia_fiscalia',
                    'gao_det'               => 'gao',
                ] as $col => $after) {
                    if (!Schema::hasColumn('fallecidos', $col)) {
                        $t->string($col, 255)->nullable()->after($after);
                    }
                }
            });
        }
    }

    private function ensureHeridos(): void
    {
        if (!Schema::hasTable('heridos')) {
            Schema::create('heridos', function (Blueprint $t) {
                $t->id();
                $t->unsignedBigInteger('caso_id')->index();

                $t->string('etiqueta', 2)->nullable();
                $t->string('nombres', 120)->nullable();
                $t->string('apellidos', 120)->nullable();
                $t->string('cedula', 20)->nullable();
                $t->unsignedSmallInteger('edad')->nullable();
                $t->char('sexo', 1)->nullable();
                $t->string('observacion', 255)->nullable();

                $t->string('alias', 120)->nullable();
                $t->string('nacionalidad', 120)->nullable();
                $t->string('ocupacion', 120)->nullable();
                $t->string('movilizacion', 120)->nullable();

                $t->string('antecedentes', 10)->nullable();
                $t->string('antecedentes_det', 255)->nullable();

                $t->string('sajte', 10)->nullable();
                $t->string('sajte_det', 255)->nullable();

                $t->string('noticia_fiscalia', 10)->nullable();
                $t->string('noticia_fiscalia_det', 255)->nullable();

                $t->string('gao', 10)->nullable();
                $t->string('gao_det', 255)->nullable();

                $t->timestamps();
            });
        } else {
            Schema::table('heridos', function (Blueprint $t) {
                foreach ([
                    'antecedentes_det'      => 'antecedentes',
                    'sajte_det'             => 'sajte',
                    'noticia_fiscalia_det'  => 'noticia_fiscalia',
                    'gao_det'               => 'gao',
                ] as $col => $after) {
                    if (!Schema::hasColumn('heridos', $col)) {
                        $t->string($col, 255)->nullable()->after($after);
                    }
                }
            });
        }
    }

    public function up(): void
    {
        $this->ensureFallecidos();
        $this->ensureHeridos();
    }

    public function down(): void
    {
        // Solo eliminamos las columnas extra si existen (no borramos tablas)
        foreach (['fallecidos','heridos'] as $tbl) {
            if (!Schema::hasTable($tbl)) continue;
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
