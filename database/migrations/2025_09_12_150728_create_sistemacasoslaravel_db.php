<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditoria', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('cedula', 20)->nullable();
            $table->dateTime('fecha_hora')->nullable();
            $table->string('ip', 45)->nullable();
        });

        Schema::create('casos', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('numero_caso', 50)->unique('numero_caso');
            $table->string('label');
            $table->date('fecha');
            $table->string('cedula', 20)->index('cedula');
            $table->string('nombre_asociado')->nullable();
            $table->text('descripcion')->nullable();
            $table->timestamp('created_at')->nullable()->useCurrent();
            $table->timestamp('updated_at')->useCurrentOnUpdate()->nullable()->useCurrent();
        });

        Schema::create('detalle_caso', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('caso_id')->index('caso_id');
            $table->string('verificacion')->nullable();
            $table->string('codigo_ecu', 50)->nullable();
            $table->string('zona', 50)->nullable();
            $table->string('subzona', 50)->nullable();
            $table->string('distrito', 50)->nullable();
            $table->string('circuito', 50)->nullable();
            $table->string('subcircuito', 50)->nullable();
            $table->string('espacio', 50)->nullable();
            $table->string('area', 50)->nullable();
            $table->string('lugar_hecho')->nullable();
            $table->string('coordenadas', 100)->nullable();
            $table->text('criminalistica')->nullable();
            $table->string('tipo_arma', 100)->nullable();
            $table->string('indicios', 50)->nullable();
            $table->string('tipo_delito', 100)->nullable();
            $table->text('motivacion')->nullable();
            $table->string('estado_caso', 50)->nullable();
            $table->text('justificacion')->nullable();
            $table->text('circunstancias')->nullable();
            $table->text('entrevistas')->nullable();
            $table->text('actividades')->nullable();
            $table->string('reporta')->nullable();
            $table->date('fecha_hecho')->nullable();
            $table->time('hora_hecho')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('plan_investigacion', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('caso_id')->index('caso_id');
            $table->string('delito', 100)->nullable();
            $table->string('fiscal', 100)->nullable();
            $table->date('fecha_hecho')->nullable();
            $table->date('fecha_asignacion')->nullable();
            $table->date('fecha_delegacion')->nullable();
            $table->date('fecha_elaboracion')->nullable();
            $table->date('fecha_actualizacion')->nullable();
            $table->text('circunstancias')->nullable();
            $table->text('hipotesis')->nullable();
            $table->json('actividades')->nullable();
            $table->timestamp('creado_en')->useCurrent();
        });

        Schema::create('planes_investigacion', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('caso_id')->unique('uk_plan_caso');
            $table->integer('creado_por');
            $table->integer('actualizado_por')->nullable();
            $table->text('objetivo_general')->nullable();
            $table->text('objetivos_especificos')->nullable();
            $table->text('alcance')->nullable();
            $table->text('metodologia')->nullable();
            $table->text('riesgos')->nullable();
            $table->text('indicadores')->nullable();
            $table->text('recursos')->nullable();
            $table->text('cronograma_json')->nullable();
            $table->dateTime('creado_el')->nullable()->useCurrent();
            $table->dateTime('actualizado_el')->nullable();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('usuarios', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->string('nickname', 50);
            $table->string('celular', 15)->nullable();
            $table->string('cedula', 20)->unique('cedula');
            $table->string('contrasena');
            $table->string('correo', 100)->nullable();
            $table->string('agencia', 50)->nullable();
            $table->string('equipo', 50)->nullable();
            $table->string('caso', 50)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->string('numero_caso', 50)->nullable();
            $table->enum('rol', ['admin', 'generador', 'editor'])->default('editor');
            $table->dateTime('ultima_conexion')->nullable();
            $table->string('ip_conexion', 45)->nullable();
        });

        Schema::create('victimas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('caso_id')->index();
            $table->enum('tipo', ['occiso', 'herido']);
            $table->string('etiqueta', 10)->nullable();
            $table->string('nombres', 120)->nullable();
            $table->string('apellidos', 120)->nullable();
            $table->string('cedula', 20)->nullable();
            $table->unsignedSmallInteger('edad')->nullable();
            $table->enum('sexo', ['M', 'F', 'I'])->nullable();
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

            $table->unique(['caso_id', 'tipo', 'etiqueta'], 'uniq_victimas_caso_tipo_etiqueta');
        });

        Schema::table('casos', function (Blueprint $table) {
            $table->foreign(['cedula'], 'casos_ibfk_1')->references(['cedula'])->on('usuarios');
        });

        Schema::table('detalle_caso', function (Blueprint $table) {
            $table->foreign(['caso_id'], 'detalle_caso_ibfk_1')->references(['id'])->on('casos');
        });

        Schema::table('plan_investigacion', function (Blueprint $table) {
            $table->foreign(['caso_id'], 'plan_investigacion_ibfk_1')->references(['caso_id'])->on('detalle_caso');
        });

        Schema::table('planes_investigacion', function (Blueprint $table) {
            $table->foreign(['caso_id'], 'fk_plan_caso')->references(['id'])->on('casos');
        });

        Schema::table('victimas', function (Blueprint $table) {
            $table->foreign(['caso_id'], 'victimas_caso_id_fk')->references(['id'])->on('casos')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('victimas', function (Blueprint $table) {
            $table->dropForeign('victimas_caso_id_fk');
        });

        Schema::table('planes_investigacion', function (Blueprint $table) {
            $table->dropForeign('fk_plan_caso');
        });

        Schema::table('plan_investigacion', function (Blueprint $table) {
            $table->dropForeign('plan_investigacion_ibfk_1');
        });

        Schema::table('detalle_caso', function (Blueprint $table) {
            $table->dropForeign('detalle_caso_ibfk_1');
        });

        Schema::table('casos', function (Blueprint $table) {
            $table->dropForeign('casos_ibfk_1');
        });

        Schema::dropIfExists('victimas');

        Schema::dropIfExists('usuarios');

        Schema::dropIfExists('users');

        Schema::dropIfExists('planes_investigacion');

        Schema::dropIfExists('plan_investigacion');

        Schema::dropIfExists('password_resets');

        Schema::dropIfExists('failed_jobs');

        Schema::dropIfExists('detalle_caso');

        Schema::dropIfExists('casos');

        Schema::dropIfExists('auditoria');
    }
};
