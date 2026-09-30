<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Propriedade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
            'perfil' => 'required|in:operador,agronomo,admin',
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
            'perfil' => 'required|in:operador,agronomo,admin',
            'status' => 'required|in:ativo,inativo',
            'password' => 'nullable|string|min:8|confirmed',
            'propriedade_ids' => 'nullable|array',
            'propriedade_ids.*' => 'integer|exists:propriedades,id',
        ]);
        if ($user->is(auth()->user()) && ($validated['status'] === 'inativo' || $validated['perfil'] !== 'admin')) {
            $message = 'Não é possível desativar ou remover seu próprio perfil de administrador.';
            if ($request->wantsJson()) {
                return response()->json(['message' => $message, 'errors' => ['status' => [$message]]], 422);
            }
            return back()->withInput()->withErrors(['status' => $message]);
        }
        if ($user->perfil === 'admin' && ($validated['status'] !== 'ativo' || $validated['perfil'] !== 'admin') && User::where('perfil', 'admin')->where('status', 'ativo')->count() <= 1) {
            $message = 'Mantenha ao menos um administrador ativo no sistema.';
            if ($request->wantsJson()) {
                return response()->json(['message' => $message, 'errors' => ['perfil' => [$message]]], 422);
            }
            return back()->withInput()->withErrors(['perfil' => $message]);
        }
        $password = $validated['password'] ?? null;
        $propriedadeIds = $validated['propriedade_ids'] ?? [];
        unset($validated['password']);
        unset($validated['propriedade_ids']);
        if ($password) $validated['password'] = Hash::make($password);
        $user->update($validated);
        $this->syncPropriedades($user, $propriedadeIds);

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
        if ($user->is(auth()->user())) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Não é possível excluir o usuário conectado.'], 422);
            }
            return back()->with('error', 'Não é possível excluir o usuário conectado.');
        }
        if ($user->perfil === 'admin' && $user->status === 'ativo' && User::where('perfil', 'admin')->where('status', 'ativo')->count() <= 1) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Mantenha ao menos um administrador ativo no sistema.'], 422);
            }
            return back()->with('error', 'Mantenha ao menos um administrador ativo no sistema.');
        }
        if ($user->tarefas()->exists() || $user->aplicacoes()->exists() || $user->recomendacoes()->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Este usuário possui tarefas, aplicações ou recomendações no histórico e não pode ser excluído. Desative o acesso pela edição.'], 422);
            }
            return back()->with('error', 'Este usuário possui tarefas, aplicações ou recomendações no histórico e não pode ser excluído. Desative o acesso pela edição.');
        }
        DB::transaction(function () use ($user) {
            $user->propriedades()->detach();
            $user->delete();
        });
        if ($request->wantsJson()) {
            return response()->json(['message' => 'Usuário excluído com sucesso.']);
        }

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário excluído com sucesso.');
    }

}
