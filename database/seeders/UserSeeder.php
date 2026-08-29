<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@agronexo.com',
            'password' => Hash::make('123123123'),
            'perfil' => 'admin',
            'status' => 'ativo',
        ]);

        User::create([
            'name' => 'João da Silva',
            'email' => 'agronomo@agronexo.com',
            'password' => Hash::make('123123123'),
            'perfil' => 'agronomo',
            'status' => 'ativo',
        ]);

        User::create([
            'name' => 'Pedro dos Santos',
            'email' => 'operador@agronexo.com',
            'password' => Hash::make('123123123'),
            'perfil' => 'operador',
            'status' => 'ativo',
        ]);
    }
}
