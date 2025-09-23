<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('victimas')) return;

        Schema::table('victimas', function (Blueprint $table) {
            // columnas “simples”
            if (!Schema::hasColumn('victimas', 'observacion')) {
                $table->string('observacion', 255)->nullable()->after('sexo');
            }

            // ya deberías tener estas, pero por si acaso:
            if (!Schema::hasColumn('victimas', 'alias')) $table->string('alias', 120)->nullable()->after('observacion');
            if (!Schema::hasColumn('victimas', 'nacionalidad')) $table->string('nacionalidad', 80)->nullable()->after('alias');
            if (!Schema::hasColumn('victimas', 'profesion_ocupacion')) $table->string('profesion_ocupacion', 120)->nullable()->after('nacionalidad');
            if (!Schema::hasColumn('victimas', 'movilizacion')) $table->string('movilizacion', 120)->nullable()->after('profesion_ocupacion');

            // booleanos (0/1) ya usados por tu controlador
            if (!Schema::hasColumn('victimas', 'antecedentes')) $table->boolean('antecedentes')->nullable()->after('movilizacion');
            if (!Schema::hasColumn('victimas', 'sajte_judicatura')) $table->boolean('sajte_judicatura')->nullable()->after('antecedentes');
            if (!Schema::hasColumn('victimas', 'noticia_del_delito_fiscalia')) $table->boolean('noticia_del_delito_fiscalia')->nullable()->after('sajte_judicatura');
            if (!Schema::hasColumn('victimas', 'pertenece_gao')) $table->boolean('pertenece_gao')->nullable()->after('noticia_del_delito_fiscalia');

            // detalles de los sí/no (los usa tu upsert)
            if (!Schema::hasColumn('victimas', 'antecedentes_det')) $table->string('antecedentes_det', 160)->nullable()->after('antecedentes');
            if (!Schema::hasColumn('victimas', 'sajte_judicatura_det')) $table->string('sajte_judicatura_det', 160)->nullable()->after('sajte_judicatura');
            if (!Schema::hasColumn('victimas', 'noticia_del_delito_fiscalia_det')) $table->string('noticia_del_delito_fiscalia_det', 160)->nullable()->after('noticia_del_delito_fiscalia');
            if (!Schema::hasColumn('victimas', 'gao_cargo_funcion')) $table->string('gao_cargo_funcion', 160)->nullable()->after('pertenece_gao');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('victimas')) return;

        Schema::table('victimas', function (Blueprint $table) {
            foreach ([
                'observacion',
                'antecedentes_det',
                'sajte_judicatura_det',
                'noticia_del_delito_fiscalia_det',
                'gao_cargo_funcion',
            ] as $col) {
                if (Schema::hasColumn('victimas', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
