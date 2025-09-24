<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('fallecidos');
		Schema::dropIfExists('heridos');
		Schema::dropIfExists('plan_investigacion');
		Schema::dropIfExists('planes_investigacion');
		Schema::dropIfExists('auditoria');
		
		
		DB::statement("DELETE FROM indicios;");
		DB::statement("DELETE FROM victimas;");
		DB::statement("DELETE FROM detalle_caso;");
		DB::statement("DELETE FROM seguimiento;");
		

		Schema::table('detalle_caso', function (Blueprint $table) {
					
			$table->dropColumn('id');
			$table->primary('caso_id');
			
		});
		
		Schema::table('seguimiento', function (Blueprint $table) {
			
			$table->integer('caso_id')->change();
			$table->foreign('caso_id')->references('caso_id')->on('detalle_caso');
		});
		
		Schema::table('victimas', function (Blueprint $table) {
			$table->dropForeign('victimas_caso_id_fk');
			$table->foreign('caso_id')->references('caso_id')->on('detalle_caso');
		});
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
