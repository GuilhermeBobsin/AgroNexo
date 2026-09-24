<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutoController extends Controller
{
    public function index(Request $request)
    {
        $query = Produto::withCount(['propriedades', 'aplicacoes', 'tarefas'])
            ->orderBy('nome');

        if ($request->filled('busca')) {
            $busca = $request->string('busca')->trim()->toString();
            $query->where(function ($subquery) use ($busca) {
                $subquery->where('nome', 'like', "%{$busca}%")
                    ->orWhere('principio_ativo', 'like', "%{$busca}%")
                    ->orWhere('grupo_modo_acao', 'like', "%{$busca}%");
            });
        }

        $produtos = $query->paginate(15)->withQueryString();
        $totalProdutos = Produto::count();
        $comEstoque = DB::table('produto_propriedade')->distinct('produto_id')->count('produto_id');

        return view('admin.produtos.index', compact('produtos', 'totalProdutos', 'comEstoque'));
    }

    public function create()
    {
        return view('admin.produtos.create');
    }

    private function regras(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'principio_ativo' => 'nullable|string|max:255',
            'grupo_modo_acao' => 'nullable|string|max:255',
            'unidade' => 'required|string|max:255',
            'preco' => 'nullable|numeric|min:0|max:9999999999.99',
        ];
    }

    public function store(Request $request)
    {
        $produto = Produto::create($request->validate($this->regras()));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Produto cadastrado com sucesso.',
                'redirect' => route('admin.produtos.show', $produto),
            ], 201);
        }

        return redirect()->route('admin.produtos.show', $produto)->with('success', 'Produto cadastrado com sucesso.');
    }

    public function show(Produto $produto)
    {
        $produto->loadCount(['propriedades', 'aplicacoes', 'tarefas']);
        $estoques = $produto->propriedades()->orderBy('nome')->get();

        return view('admin.produtos.show', compact('produto', 'estoques'));
    }

    public function edit(Produto $produto)
    {
        return view('admin.produtos.edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $produto->update($request->validate($this->regras()));

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Produto atualizado com sucesso.',
                'redirect' => route('admin.produtos.show', $produto),
            ]);
        }

        return redirect()->route('admin.produtos.show', $produto)->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Request $request, Produto $produto)
    {
        $temAplicacoes = $produto->aplicacoes()->exists();
        $temTarefas = $produto->tarefas()->exists();

        if ($temAplicacoes || $temTarefas) {
            $message = 'Este produto não pode ser removido porque está vinculado a aplicações ou tarefas.';

            return response()->json(['message' => $message], 409);
        }

        $produto->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Produto removido com sucesso.']);
        }

        return redirect()->route('admin.produtos.index')->with('success', 'Produto removido com sucesso.');
    }
}
