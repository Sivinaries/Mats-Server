<?php

namespace Database\Seeders;

use App\Models\Profil;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProfilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch or create a user to associate the profil with
        $user = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => bcrypt('123456'),
                'level' => 'Admin',
            ]
        );
    }
}
