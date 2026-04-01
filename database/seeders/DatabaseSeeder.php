<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Créer le compte administrateur
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@immonow.cm',
            'password' => Hash::make('admin1234'),
            'is_admin' => true,
            'phone'    => '699000000',
        ]);

        // Créer un utilisateur de test normal
        User::create([
            'name'     => 'Jean Dupont',
            'email'    => 'jean@test.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'phone'    => '677123456',
        ]);

        $this->command->info('✅ Comptes créés :');
        $this->command->info('   Admin   → admin@immonow.cm / admin1234');
        $this->command->info('   User    → jean@test.com / password');
    }
}
