<?php

namespace App\Http\Controllers\Agronomo;

use App\Http\Controllers\Controller;
use App\Models\Produto;
use App\Models\Propriedade;
use App\Models\Recomendacao;
use App\Models\User;
use App\Notifications\AtualizacaoOperacional;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecomendacaoController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'status' => ['nullable', Rule::in(['pendente', 'tarefa_criada', 'recusada'])],
        ]);
        $propriedadeIds = auth()->user()->propriedades()->pluck('propriedades.id');
        $query = Recomendacao::with(['propriedade', 'talhao', 'tarefa'])
            ->where('agronomo_id', auth()->id())
            ->whereIn('propriedade_id', $propriedadeIds)
            ->latest();
        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        $recomendacoes = $query->paginate(15)->withQueryString();

        return view('agronomo.recomendacoes.index', compact('recomendacoes'));
    }

    public function create()
    {
        $propriedades = auth()->user()->propriedades()->with(['talhoes', 'produtos'])->orderBy('nome')->get();
        return view('agronomo.recomendacoes.create', compact('propriedades'));
    }

    public function store(Request $request)
    {
        $propriedadeIds = auth()->user()->propriedades()->pluck('propriedades.id')->all();
        $validated = $request->validate([
            'propriedade_id' => ['required', 'integer', Rule::in($propriedadeIds)],
            'talhao_id' => ['nullable', 'integer', Rule::exists('talhoes', 'id')->where('propriedade_id', $request->input('propriedade_id'))],
            'titulo' => ['required', 'string', 'max:255'],
            'tipo' => ['required', Rule::in(['aplicacao', 'aracao', 'calagem', 'irrigacao', 'manutencao', 'outro'])],
            'prioridade' => ['required', Rule::in(['baixa', 'normal', 'alta', 'urgente'])],
            'diagnostico' => ['required', 'string', 'min:10', 'max:5000'],
            'orientacao' => ['required', 'string', 'min:10', 'max:5000'],
            'produto_id' => ['nullable', 'integer'],
            'dose' => ['nullable', 'numeric', 'min:0.001', 'max:9999999.999'],
        ]);

        if ($validated['tipo'] === 'aplicacao') {
            $request->validate([
                'talhao_id' => ['required', 'integer', Rule::exists('talhoes', 'id')->where('propriedade_id', $validated['propriedade_id'])],
                'produto_id' => ['required', 'integer', Rule::exists('produto_propriedade', 'produto_id')->where('propriedade_id', $validated['propriedade_id'])],
                'dose' => ['required', 'numeric', 'min:0.001', 'max:9999999.999'],
            ]);
        } elseif (!empty($validated['produto_id'])) {
            $request->validate([
                'produto_id' => ['integer', Rule::exists('produto_propriedade', 'produto_id')->where('propriedade_id', $validated['propriedade_id'])],
            ]);
        }

        if ($validated['tipo'] !== 'aplicacao') {
            $validated['produto_id'] = null;
            $validated['dose'] = null;
        }
        $validated['agronomo_id'] = auth()->id();

        $recomendacao = Recomendacao::create($validated);

        User::where('perfil', 'admin')->where('status', 'ativo')->each(function (User $admin) use ($recomendacao) {
            $admin->notify(new AtualizacaoOperacional(
                'Nova recomendação técnica',
                auth()->user()->name . ' enviou: ' . $recomendacao->titulo,
                route('admin.recomendacoes.show', $recomendacao),
                'clipboard-check'
            ));
        });

        return redirect()->route('agronomo.recomendacoes.show', $recomendacao)
            ->with('success', 'Recomendação enviada para análise do administrador.');
    }

    public function show(Recomendacao $recomendacao)
    {
        abort_unless(
            $recomendacao->agronomo_id === auth()->id()
            && auth()->user()->propriedades()->whereKey($recomendacao->propriedade_id)->exists(),
            404
        );
        $recomendacao->load(['propriedade', 'talhao.cultura', 'produto', 'analisadoPor', 'tarefa']);

        return view('agronomo.recomendacoes.show', compact('recomendacao'));
    }
}
