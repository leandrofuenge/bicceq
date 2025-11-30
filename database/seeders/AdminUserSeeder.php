<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@bicceq.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
            'is_admin' => true, // Se tiver campo de admin
        ]);

        $this->command->info('Usuário administrador criado:');
        $this->command->info('E-mail: admin@bicceq.com');
        $this->command->info('Senha: admin123');
    }
}