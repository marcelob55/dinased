<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('seguimiento', function (Blueprint $table) {
            $table->id();

            // 👇 Debe coincidir con casos.id (bigint unsigned si usaste $table->id() en 'casos')
            $table->unsignedBigInteger('caso_id');
            $table->foreign('caso_id')
                  ->references('id')->on('casos')
                  ->onDelete('cascade')->onUpdate('cascade');

            // Clave judicial
            $table->char('no_causa_no_fiscalia', 15);

            // Selecciones/catálogos
            $table->string('nombres_del_fiscal_delegado', 200)->nullable();
            $table->string('fiscalia_nombre', 100)->nullable();
            $table->string('fiscalia_numero', 10)->nullable();

            $table->string('tipo_penal_en_audiencia_de_formulacion_de_cargos', 120)->nullable();
            $table->string('tipo_de_medidas', 200)->nullable();
            $table->string('detalle_de_medidas', 500)->nullable();

            $table->enum('existio_vinculacion_dentro_de_la_instruccion_fiscal', ['SI','NO'])->default('NO');
            $table->text('nombre_del_o_los_vinculados')->nullable();

            $table->string('situacion_juridica_actual', 120)->nullable();

            // Multi-selects (serializados)
            $table->text('requerimientos_realizados')->nullable();
            $table->text('requerimientos_pendientes')->nullable();

            $table->text('observacion')->nullable();

            // Escenas
            $table->string('escena_levantamiento', 100)->nullable();
            $table->string('escena_suceso', 100)->nullable();

            $table->timestamps();

            $table->index('no_causa_no_fiscalia');
            $table->index('caso_id');
        });
    }

    public function down(): void {
        Schema::dropIfExists('seguimiento');
    }
};
