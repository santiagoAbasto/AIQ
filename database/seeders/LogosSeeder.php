<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Logo;

class LogosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear registros de logos ficticios
        Logo::create([
            'logo_header' => 'header_logo.png',
            'logo_footer' => 'footer_logo.png',
            'favicon' => 'footer_logo.png',
        ]);

        // Puedes agregar más registros según sea necesario
    }
}
