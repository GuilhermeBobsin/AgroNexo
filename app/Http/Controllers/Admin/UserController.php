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
        $usuarios = User::orderBy('name')->paginate(12);
        return view('admin.usuarios.index', compact('usuarios', 'contagem'));
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
            return back()->withInput()->withErrors(['status' => 'Não é possível desativar ou remover seu próprio perfil de administrador.']);
        }
        if ($user->perfil === 'admin' && ($validated['status'] !== 'ativo' || $validated['perfil'] !== 'admin') && User::where('perfil', 'admin')->where('status', 'ativo')->count() <= 1) {
            return back()->withInput()->withErrors(['perfil' => 'Mantenha ao menos um administrador ativo no sistema.']);
        }
        $password = $validated['password'] ?? null;
        $propriedadeIds = $validated['propriedade_ids'] ?? [];
        unset($validated['password']);
        unset($validated['propriedade_ids']);
        if ($password) $validated['password'] = Hash::make($password);
        $user->update($validated);
        $this->syncPropriedades($user, $propriedadeIds);

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
            return back()->with('error', 'Não é possível excluir o usuário conectado.');
        }
        if ($user->perfil === 'admin' && $user->status === 'ativo' && User::where('perfil', 'admin')->where('status', 'ativo')->count() <= 1) {
            return back()->with('error', 'Mantenha ao menos um administrador ativo no sistema.');
        }
        if ($user->tarefas()->exists() || $user->aplicacoes()->exists()) {
            return back()->with('error', 'Este usuário possui tarefas ou aplicações no histórico e não pode ser excluído. Desative o acesso pela edição.');
        }
        DB::transaction(function () use ($user) {
            $user->propriedades()->detach();
            $user->delete();
        });
        return redirect()->route('admin.usuarios.index')->with('success', 'Usuário excluído com sucesso.');
    }

}
