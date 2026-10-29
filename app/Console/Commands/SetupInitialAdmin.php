<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SetupInitialAdmin extends Command
{
    protected $signature = 'agronexo:setup-admin';

    protected $description = 'Cria o primeiro administrador do AgroNexo (setup único via terminal).';

    public function handle(): int
    {
        if (User::where('perfil', 'admin')->exists()) {
            $this->error('Já existe um administrador. O setup inicial não pode ser repetido.');

            return self::FAILURE;
        }

        $name = trim((string) $this->ask('Nome do administrador'));
        $email = strtolower(trim((string) $this->ask('E-mail')));
        $password = (string) $this->secret('Senha (mínimo de 12 caracteres)');
        $confirmation = (string) $this->secret('Confirme a senha');

        $validator = validator(
            compact('name', 'email', 'password', 'confirmation'),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:12', 'same:confirmation'],
                'confirmation' => ['required'],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        try {
            DB::transaction(function () use ($name, $email, $password): void {
                if (User::where('perfil', 'admin')->lockForUpdate()->exists()) {
                    throw ValidationException::withMessages(['admin' => 'Já existe um administrador.']);
                }

                User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'perfil' => 'admin',
                    'status' => 'ativo',
                ]);
            });
        } catch (ValidationException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Administrador inicial criado. Remova/roteie o acesso ao terminal de setup conforme a política operacional do servidor.');

        return self::SUCCESS;
    }
}
