<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
		 if (!Schema::hasTable('fallecidos') && !Schema::hasTable('heridos')) {
        return;
    }
		
		// === FALLECIDOS ===
        Schema::table('fallecidos', function (Blueprint $t) {
            if (!Schema::hasColumn('fallecidos', 'antecedentes_det')) {
                $t->string('antecedentes_det', 255)->nullable()->after('antecedentes');
            }
            if (!Schema::hasColumn('fallecidos', 'sajte_det')) {
                $t->string('sajte_det', 255)->nullable()->after('sajte');
            }
            if (!Schema::hasColumn('fallecidos', 'noticia_fiscalia_det')) {
                $t->string('noticia_fiscalia_det', 255)->nullable()->after('noticia_fiscalia');
            }
            if (!Schema::hasColumn('fallecidos', 'gao_det')) {
                $t->string('gao_det', 255)->nullable()->after('gao');
            }
        });

        // === HERIDOS ===
        Schema::table('heridos', function (Blueprint $t) {
            if (!Schema::hasColumn('heridos', 'antecedentes_det')) {
                $t->string('antecedentes_det', 255)->nullable()->after('antecedentes');
            }
            if (!Schema::hasColumn('heridos', 'sajte_det')) {
                $t->string('sajte_det', 255)->nullable()->after('sajte');
            }
            if (!Schema::hasColumn('heridos', 'noticia_fiscalia_det')) {
                $t->string('noticia_fiscalia_det', 255)->nullable()->after('noticia_fiscalia');
            }
            if (!Schema::hasColumn('heridos', 'gao_det')) {
                $t->string('gao_det', 255)->nullable()->after('gao');
            }
        });
    }

    public function down(): void
    {
        // === FALLECIDOS ===
        Schema::table('fallecidos', function (Blueprint $t) {
            if (Schema::hasColumn('fallecidos', 'gao_det')) {
                $t->dropColumn('gao_det');
            }
            if (Schema::hasColumn('fallecidos', 'noticia_fiscalia_det')) {
                $t->dropColumn('noticia_fiscalia_det');
            }
            if (Schema::hasColumn('fallecidos', 'sajte_det')) {
                $t->dropColumn('sajte_det');
            }
            if (Schema::hasColumn('fallecidos', 'antecedentes_det')) {
                $t->dropColumn('antecedentes_det');
            }
        });

        // === HERIDOS ===
        Schema::table('heridos', function (Blueprint $t) {
            if (Schema::hasColumn('heridos', 'gao_det')) {
                $t->dropColumn('gao_det');
            }
            if (Schema::hasColumn('heridos', 'noticia_fiscalia_det')) {
                $t->dropColumn('noticia_fiscalia_det');
            }
            if (Schema::hasColumn('heridos', 'sajte_det')) {
                $t->dropColumn('sajte_det');
            }
            if (Schema::hasColumn('heridos', 'antecedentes_det')) {
                $t->dropColumn('antecedentes_det');
            }
        });
    }
};
