<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RedesSeeder extends Seeder
{
    public function run()
    {
        DB::table('redes')->insert([
            [
                'instagram' => 'https://www.instagram.com/your_instagram',
                'facebook' => 'https://www.facebook.com/your_facebook',
                'youtube' => 'https://www.youtube.com/your_youtube',
                'linkedin' => 'https://www.linkedin.com/your_linkedin',
                'tiktok' => 'https://www.tiktok.com/@your_tiktok',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Añade más registros aquí si es necesario
        ]);
    }
}
