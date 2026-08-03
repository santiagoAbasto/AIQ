<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inicio;

class InicioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Crear un único registro para la tabla 'inicios'
        Inicio::create([
            'titulo' => 'Título de ejemplo',
            'descripcion' => 'Descripción de ejemplo',
            'imagen' => 'public/inicio/ejemplo.jpg', // Ruta de la imagen de ejemplo
            'titulo_banner' => 'Título del banner de ejemplo',
            'descripcion_banner' => 'Descripción del banner de ejemplo',
            'banner' => 'public/inicio/banner/ejemplo.jpg', // Ruta de la imagen del banner de ejemplo
        ]);
    }
}
