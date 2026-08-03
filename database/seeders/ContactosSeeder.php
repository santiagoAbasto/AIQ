<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContactosSeeder extends Seeder
{
    public function run()
    {
        DB::table('contactos')->insert([
            [
                'enlace' => 'https://www.your_website.com',
                'mapa' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3171.835446819971!2d-122.08424968469216!3d37.42206597982382!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x808fb5b7e1a8ef8f%3A0xdf1c8e29e1f0b29b!2sGoogleplex!5e0!3m2!1sen!2sus!4v1607110138546!5m2!1sen!2sus',
                'direccion' => '1600 Amphitheatre Parkway, Mountain View, CA',
                'telefono' => '+1 650-253-0000',
                'whatsapp' => '+5491112345678',
                'correo' => 'contact@yourdomain.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Añade más registros aquí si es necesario
        ]);
    }
}
