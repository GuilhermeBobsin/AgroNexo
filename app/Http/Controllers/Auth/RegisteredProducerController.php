<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Propriedade;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RegisteredProducerController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $dados = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'confirmed'],
            'propriedade_nome' => ['required', 'string', 'max:255'],
            'propriedade_localizacao' => ['required', 'string', 'max:255'],
        ]);

        $user = DB::transaction(function () use ($dados): User {
            $user = User::create([
                'name' => $dados['name'],
                'email' => $dados['email'],
                'password' => Hash::make($dados['password']),
                'perfil' => 'admin',
                'status' => 'ativo',
            ]);

            $propriedade = Propriedade::create([
                'nome' => $dados['propriedade_nome'],
                'localizacao' => $dados['propriedade_localizacao'],
            ]);
            $user->propriedades()->attach($propriedade->id, ['papel' => 'dono']);

            return $user;
        });

        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.admin')->with('success', 'Conta e propriedade criadas. Complete os dados da fazenda para começar.');
    }
}
