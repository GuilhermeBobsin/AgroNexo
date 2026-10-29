<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Propriedade;
use App\Models\MovimentacaoEstoque;
use App\Models\Tarefa;
use App\Models\Aplicacao;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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
            'estoque_atual' => 'required|numeric|decimal:0,3|min:0|max:999999999.999',
            'estoque_minimo' => 'required|numeric|decimal:0,3|min:0|max:999999999.999',
            'data_validade' => 'nullable|date',
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->regras($request));
        $registro = DB::transaction(function () use ($validated) {
            $now = now();
            $registro = DB::table('produto_propriedade')->insertGetId([
                ...$validated,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $saldo = (float) $validated['estoque_atual'];
            if ($saldo > 0) {
                MovimentacaoEstoque::create([
                    'produto_propriedade_id' => $registro,
                    'usuario_id' => auth()->id(),
                    'tipo' => 'entrada',
                    'quantidade' => $saldo,
                    'saldo_anterior' => 0,
                    'saldo_atual' => $saldo,
                    'motivo' => 'Saldo inicial do cadastro',
                ]);
            }

            return $registro;
        });

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
        $movimentacoes = MovimentacaoEstoque::with(['usuario:id,name', 'tarefa:id,titulo'])
            ->where('produto_propriedade_id', $estoque)
            ->latest()
            ->paginate(15);

        return view('admin.estoque.show', ['estoque' => $registro, 'movimentacoes' => $movimentacoes]);
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
        DB::transaction(function () use ($request, $validated, $estoque) {
            $registro = DB::table('produto_propriedade')->where('id', $estoque)->lockForUpdate()->firstOrFail();
            $anterior = (float) $registro->estoque_atual;
            $novo = (float) $validated['estoque_atual'];
            $diferenca = round($novo - $anterior, 3);
            if ($diferenca !== 0.0 && !$request->filled('motivo_ajuste')) {
                throw ValidationException::withMessages(['motivo_ajuste' => 'Informe o motivo quando alterar a quantidade atual.']);
            }
            if ($request->filled('motivo_ajuste')) {
                validator($request->only('motivo_ajuste'), ['motivo_ajuste' => 'required|string|max:255'])->validate();
            }
            DB::table('produto_propriedade')->where('id', $estoque)->update([...$validated, 'updated_at' => now()]);
            if ($diferenca !== 0.0) {
                MovimentacaoEstoque::create([
                    'produto_propriedade_id' => $estoque,
                    'usuario_id' => auth()->id(),
                    'tipo' => 'ajuste',
                    'quantidade' => abs($diferenca),
                    'saldo_anterior' => $anterior,
                    'saldo_atual' => $novo,
                    'motivo' => $request->string('motivo_ajuste')->trim()->toString(),
                ]);
            }
        });

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
        $message = DB::transaction(function () use ($estoque) {
            $registro = DB::table('produto_propriedade')->where('id', $estoque)->lockForUpdate()->first();
            abort_unless($registro, 404);
            if (MovimentacaoEstoque::where('produto_propriedade_id', $estoque)->exists()) {
                return 'Este estoque possui movimentações no histórico e não pode ser removido.';
            }
            if (Tarefa::where('produto_id', $registro->produto_id)->where('propriedade_id', $registro->propriedade_id)->exists()
                || Aplicacao::where('produto_id', $registro->produto_id)->whereHas('talhao', fn ($q) => $q->where('propriedade_id', $registro->propriedade_id))->exists()) {
                return 'Este estoque está vinculado a tarefas ou aplicações e não pode ser removido.';
            }
            DB::table('produto_propriedade')->where('id', $estoque)->delete();
            return null;
        });
        if ($message) {
            return $request->wantsJson()
                ? response()->json(['message' => $message], 409)
                : back()->with('error', $message);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Registro de estoque removido com sucesso.']);
        }

        return redirect()->route('admin.estoque.index')->with('success', 'Registro de estoque removido com sucesso.');
    }
}
