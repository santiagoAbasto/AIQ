<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = env('AIQ_ADMIN_PASSWORD');

        if (! filled($password)) {
            $this->command?->warn(
                'Administrador inicial omitido: configurá AIQ_ADMIN_PASSWORD para crearlo.'
            );

            return;
        }

        $username = env('AIQ_ADMIN_USERNAME', 'admin');
        $email = env('AIQ_ADMIN_EMAIL');

        if (! filled($email)) {
            $this->command?->warn(
                'Administrador inicial omitido: configurá AIQ_ADMIN_EMAIL para crearlo.'
            );

            return;
        }

        DB::table('users')->updateOrInsert(
            ['email' => $email],
            [
                'name' => env('AIQ_ADMIN_NAME', 'Administrador AIQ'),
                'username' => $username,
                'role' => 'Administrador',
                'password' => Hash::make($password),
                'remember_token' => Str::random(10),
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
