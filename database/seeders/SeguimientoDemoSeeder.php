<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SeguimientoDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Cambia el caso_id por un ID real de tu tabla casos
        DB::table('seguimiento')->insert([
            'caso_id' => 1,
            'no_causa_no_fiscalia' => '090101825010373',
            'nombres_del_fiscal_delegado' => 'ABG. INTRIAGO MARTINEZ WILMER JEAN CARLOS',
            'fiscalia_nombre' => 'FISCALÍA PROVINCIAL',
            'fiscalia_numero' => '01',
            'tipo_penal_en_audiencia_de_formulacion_de_cargos' => 'ASESINATO',
            'tipo_de_medidas' => 'MEDIDAS CAUTELARES',
            'detalle_de_medidas' => 'PRISIÓN PREVENTIVA',
            'existio_vinculacion_dentro_de_la_instruccion_fiscal' => 'SI',
            'nombre_del_o_los_vinculados' => 'CARLOS TORRES TORRES',
            'situacion_juridica_actual' => 'ETAPA DE JUICIO',
            'requerimientos_realizados' => 'PERICIA TELEFÓNICA',
            'requerimientos_pendientes' => 'PERICIA OPERADORAS',
            'observacion' => 'Registro de ejemplo',
            'escena_levantamiento' => 'VÍA PÚBLICA',
            'escena_suceso' => 'VÍA PÚBLICA',
            'created_at' => now(), 'updated_at' => now(),
        ]);
    }
}
