<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Limpar tabela primeiro
        User::truncate();

        // Criar usuários de teste
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@bicceq.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Usuário Teste', 
            'email' => 'teste@bicceq.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Vendedor',
            'email' => 'vendedor@bicceq.com',
            'password' => Hash::make('vendedor123'),
            'email_verified_at' => now(),
        ]);
    }
}