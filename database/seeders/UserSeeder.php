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
        if (!app()->environment(['local', 'testing'])) {
            return;
        }

        User::updateOrCreate(['email' => 'agronomo@agronexo.com'], [
            'name' => 'João da Silva',
            'password' => Hash::make('123123123'),
            'perfil' => 'agronomo',
            'status' => 'ativo',
        ]);

        User::updateOrCreate(['email' => 'operador@agronexo.com'], [
            'name' => 'Pedro dos Santos',
            'password' => Hash::make('123123123'),
            'perfil' => 'operador',
            'status' => 'ativo',
        ]);
    }
}
