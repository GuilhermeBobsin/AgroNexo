<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Propriedade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function index()
    {
        $contagem = User::count();
        $usuarios = User::with('propriedades:id,nome')->orderBy('name')->paginate(12);
        $propriedades = Propriedade::orderBy('nome')->get(['id', 'nome']);
        return view('admin.usuarios.index', compact('usuarios', 'contagem', 'propriedades'));
    }

    public function create()
    {
        $propriedades = Propriedade::orderBy('nome')->get(['id', 'nome']);
        return view('admin.usuarios.create', compact('propriedades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            // Administradores são provisionados pelo setup único no terminal, nunca por cadastro web.
            'perfil' => 'required|in:operador,agronomo',
            'propriedade_ids' => 'nullable|array',
            'propriedade_ids.*' => 'integer|exists:propriedades,id',
        ]);

        $propriedadeIds = $validated['propriedade_ids'] ?? [];
        unset($validated['propriedade_ids']);
        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);
        $this->syncPropriedades($user, $propriedadeIds);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Usuário criado com sucesso.',
                'redirect' => route('admin.usuarios.index'),
            ], 201);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário criado com sucesso.');
    }

    public function edit(User $user)
    {
        $propriedades = Propriedade::orderBy('nome')->get(['id', 'nome']);
        $propriedadesSelecionadas = $user->propriedades()->pluck('propriedades.id')->all();
        return view('admin.usuarios.edit', compact('user', 'propriedades', 'propriedadesSelecionadas'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'perfil' => ['required', Rule::in($user->perfil === 'admin'
                ? ['operador', 'agronomo', 'admin']
                : ['operador', 'agronomo'])],
            'status' => 'required|in:ativo,inativo',
            'password' => 'nullable|string|min:8|confirmed',
            'propriedade_ids' => 'nullable|array',
            'propriedade_ids.*' => 'integer|exists:propriedades,id',
        ]);
        $password = $validated['password'] ?? null;
        $propriedadeIds = $validated['propriedade_ids'] ?? [];
        unset($validated['password']);
        unset($validated['propriedade_ids']);
        if ($password) $validated['password'] = Hash::make($password);
        DB::transaction(function () use ($request, $user, $validated, $propriedadeIds): void {
            $administradoresAtivos = User::where('perfil', 'admin')->where('status', 'ativo')->lockForUpdate()->get();
            if ($user->is($request->user()) && ($validated['status'] === 'inativo' || $validated['perfil'] !== 'admin')) {
                throw ValidationException::withMessages(['status' => 'Não é possível desativar ou remover seu próprio perfil de administrador.']);
            }
            if ($user->perfil === 'admin' && ($validated['status'] !== 'ativo' || $validated['perfil'] !== 'admin') && $administradoresAtivos->count() <= 1) {
                throw ValidationException::withMessages(['perfil' => 'Mantenha ao menos um administrador ativo no sistema.']);
            }

            $user->update($validated);
            $this->syncPropriedades($user, $propriedadeIds);
        });

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Usuário atualizado com sucesso.',
                'redirect' => route('admin.usuarios.index'),
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'perfil' => $user->perfil,
                    'status' => $user->status,
                    'propriedade_ids' => $propriedadeIds,
                ],
            ]);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário atualizado com sucesso.');
    }

    private function syncPropriedades(User $user, array $ids): void
    {
        $papel = $user->perfil === 'agronomo' ? 'agronomo' : 'colaborador';
        $user->propriedades()->sync(collect($ids)->mapWithKeys(fn ($id) => [$id => ['papel' => $papel]])->all());
    }

    public function destroy(Request $request, User $user)
    {
        try {
            DB::transaction(function () use ($user, $request): void {
                $administradoresAtivos = User::where('perfil', 'admin')->where('status', 'ativo')->lockForUpdate()->get();
                if ($user->is($request->user())) {
                    throw ValidationException::withMessages(['user' => 'Não é possível excluir o usuário conectado.']);
                }
                if ($user->perfil === 'admin' && $user->status === 'ativo' && $administradoresAtivos->count() <= 1) {
                    throw ValidationException::withMessages(['user' => 'Mantenha ao menos um administrador ativo no sistema.']);
                }
                if ($user->tarefas()->exists() || $user->aplicacoes()->exists() || $user->recomendacoes()->exists()) {
                    throw ValidationException::withMessages(['user' => 'Este usuário possui tarefas, aplicações ou recomendações no histórico e não pode ser excluído. Desative o acesso pela edição.']);
                }
                $user->propriedades()->detach();
                $user->delete();
            });
        } catch (ValidationException $exception) {
            $message = collect($exception->errors())->flatten()->first() ?? 'Não foi possível excluir o usuário.';
            return $request->wantsJson()
                ? response()->json(['message' => $message, 'errors' => $exception->errors()], 422)
                : back()->with('error', $message);
        }
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Usuário excluído com sucesso.']);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário excluído com sucesso.');
    }

}
