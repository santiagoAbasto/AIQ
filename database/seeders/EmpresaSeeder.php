<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Empresa;

class EmpresaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear un único registro para la tabla 'empresas'
        Empresa::create([
            'titulo' => 'Título de la empresa',
            'descripcion' => 'Descripción de la empresa',
            'texto_mision' => 'Descripción de la empresa der',
            'texto_vision' => 'Descripción de la empresa der',
            'texto_valores' => 'Descripción de la empresa der',
             'imagen' => 'public/empresa/ejemplo.jpg', // Ruta de la imagen de ejemplo
             'icono_mision' => 'public/empresa/ejemplo.jpg', // Ruta de la icono de ejemplo
             'icono_vision' => 'public/empresa/ejemplo.jpg', // Ruta de la icono de ejemplo
             'icono_valores' => 'public/empresa/ejemplo.jpg', // Ruta de la icono de ejemplo
           // 'galeria' => '[]', // Ruta de la galería de ejemplo
            
        ]);
    }
}
