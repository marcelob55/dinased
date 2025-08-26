<?php

// database/migrations/2025_08_26_124847_create_victimas_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('victimas', function (Blueprint $table) {
            $table->bigIncrements('id');               // puede ser bigIncrements sin problema
            $table->integer('caso_id');                // <-- igual que casos.id (int, SIGNED)

            $table->enum('tipo', ['occiso','herido']);
            $table->string('etiqueta', 10)->nullable();
            $table->string('nombres', 120)->nullable();
            $table->string('apellidos', 120)->nullable();
            $table->string('cedula', 20)->nullable();
            $table->unsignedSmallInteger('edad')->nullable();
            $table->enum('sexo', ['M','F','I'])->nullable();

            // campos extra
            $table->string('alias')->nullable();
            $table->string('nacionalidad')->nullable();
            $table->string('profesion_ocupacion')->nullable();
            $table->string('movilizacion')->nullable();
            $table->tinyInteger('antecedentes')->nullable();
            $table->tinyInteger('sajte_judicatura')->nullable();
            $table->tinyInteger('noticia_del_delito_fiscalia')->nullable();
            $table->tinyInteger('pertenece_gao')->nullable();
            $table->string('gao_cargo_funcion')->nullable();

            $table->timestamps();

            // índice + FK (casos.id es INT SIGNED)
            $table->index('caso_id', 'victimas_caso_id_index');
            $table->foreign('caso_id', 'victimas_caso_id_fk')
                  ->references('id')->on('casos')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('victimas');
    }
};
