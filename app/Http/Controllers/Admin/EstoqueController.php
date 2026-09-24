<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Propriedade;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EstoqueController extends Controller
{
    private function registros(): Builder
    {
        return DB::table('produto_propriedade as estoque')
            ->join('produtos', 'produtos.id', '=', 'estoque.produto_id')
            ->join('propriedades', 'propriedades.id', '=', 'estoque.propriedade_id')
            ->select(
                'estoque.id', 'estoque.produto_id', 'estoque.propriedade_id',
                'estoque.estoque_atual', 'estoque.estoque_minimo', 'estoque.data_validade',
                'estoque.created_at', 'estoque.updated_at',
                'produtos.nome as produto_nome', 'produtos.unidade', 'produtos.principio_ativo',
                'produtos.grupo_modo_acao', 'produtos.preco',
                'propriedades.nome as propriedade_nome'
            );
    }

    public function index(Request $request)
    {
        $query = $this->registros()->orderBy('propriedades.nome')->orderBy('produtos.nome');

        if ($request->filled('busca')) {
            $busca = $request->string('busca')->trim()->toString();
            $query->where(function (Builder $subquery) use ($busca) {
                $subquery->where('produtos.nome', 'like', "%{$busca}%")
                    ->orWhere('propriedades.nome', 'like', "%{$busca}%")
                    ->orWhere('produtos.principio_ativo', 'like', "%{$busca}%");
            });
        }

        if ($request->filled('propriedade_id')) {
            $query->where('estoque.propriedade_id', $request->integer('propriedade_id'));
        }

        $estoques = $query->paginate(15)->withQueryString();
        $propriedades = Propriedade::orderBy('nome')->get(['id', 'nome']);
        $resumo = DB::table('produto_propriedade')
            ->selectRaw('count(*) as total')
            ->selectRaw('sum(case when estoque_atual <= estoque_minimo then 1 else 0 end) as baixos')
            ->selectRaw('sum(case when data_validade < ? then 1 else 0 end) as vencidos', [today()->toDateString()])
            ->first();

        return view('admin.estoque.index', compact('estoques', 'propriedades', 'resumo'));
    }

    public function create()
    {
        $produtos = Produto::orderBy('nome')->get(['id', 'nome', 'unidade']);
        $propriedades = Propriedade::orderBy('nome')->get(['id', 'nome']);

        return view('admin.estoque.create', compact('produtos', 'propriedades'));
    }

    private function regras(Request $request, ?int $estoqueId = null): array
    {
        return [
            'produto_id' => [
                'required', 'integer', 'exists:produtos,id',
                Rule::unique('produto_propriedade', 'produto_id')
                    ->where('propriedade_id', $request->input('propriedade_id'))
                    ->ignore($estoqueId),
            ],
            'propriedade_id' => 'required|integer|exists:propriedades,id',
            'estoque_atual' => 'required|numeric|min:0|max:999999999.999',
            'estoque_minimo' => 'required|numeric|min:0|max:999999999.999',
            'data_validade' => 'nullable|date',
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->regras($request));
        $registro = DB::table('produto_propriedade')->insertGetId([
            ...$validated,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Estoque cadastrado com sucesso.',
                'redirect' => route('admin.estoque.show', $registro),
            ], 201);
        }

        return redirect()->route('admin.estoque.show', $registro)->with('success', 'Estoque cadastrado com sucesso.');
    }

    public function show(int $estoque)
    {
        $registro = $this->registros()->where('estoque.id', $estoque)->firstOrFail();

        return view('admin.estoque.show', ['estoque' => $registro]);
    }

    public function edit(int $estoque)
    {
        $registro = DB::table('produto_propriedade')->where('id', $estoque)->firstOrFail();
        $produtos = Produto::orderBy('nome')->get(['id', 'nome', 'unidade']);
        $propriedades = Propriedade::orderBy('nome')->get(['id', 'nome']);

        return view('admin.estoque.edit', ['estoque' => $registro, 'produtos' => $produtos, 'propriedades' => $propriedades]);
    }

    public function update(Request $request, int $estoque)
    {
        $validated = $request->validate($this->regras($request, $estoque));
        $validated['updated_at'] = now();
        DB::table('produto_propriedade')->where('id', $estoque)->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Estoque atualizado com sucesso.',
                'redirect' => route('admin.estoque.show', $estoque),
            ]);
        }

        return redirect()->route('admin.estoque.show', $estoque)->with('success', 'Estoque atualizado com sucesso.');
    }

    public function destroy(Request $request, int $estoque)
    {
        DB::table('produto_propriedade')->where('id', $estoque)->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Registro de estoque removido com sucesso.']);
        }

        return redirect()->route('admin.estoque.index')->with('success', 'Registro de estoque removido com sucesso.');
    }
}
