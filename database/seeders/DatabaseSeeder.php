<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            // Usuarios básicos (admin)
            UsuarioAdminSeeder::class,

            // Caso de ejemplo (si lo creaste)
            CasoDemoSeeder::class,

            // Solo para el módulo de Seguimiento Judicial
            SeguimientoDemoSeeder::class,
        ]);
    }
}
