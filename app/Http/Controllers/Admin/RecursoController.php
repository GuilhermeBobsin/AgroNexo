<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Propriedade;
use App\Models\Recurso;
use Illuminate\Http\Request;
use App\Models\Tarefa;

class RecursoController extends Controller
{
    public function index()
    {
        $recursos = Recurso::with('propriedade')->orderBy('nome')->paginate(15);
        $contagens = Recurso::selectRaw("count(*) as total, sum(status = 'disponivel') as disponiveis, sum(status = 'em_uso') as em_uso, sum(status = 'manutencao') as manutencao")->first();

        return view('admin.recursos.index', compact('recursos', 'contagens'));
    }

    public function create()
    {
        $propriedades = Propriedade::orderBy('nome')->get();

        return view('admin.recursos.create', compact('propriedades'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'propriedade_id' => 'required|exists:propriedades,id',
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:trator,implemento,pulverizador,colheitadeira,outro',
            'status' => 'required|in:disponivel,em_uso,manutencao'
        ]);

        Recurso::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Recurso cadastrado com sucesso.',
                'redirect' => route('admin.recursos.index'),
            ], 201);
        }

        return redirect()->route('admin.recursos.index')->with('success', 'Recurso cadastrado com sucesso.');
    }

    public function show(Recurso $recurso)
    {
        $recurso->load('propriedade');

        return view('admin.recursos.show', compact('recurso'));
    }

    public function edit(Recurso $recurso)
    {
        $propriedades = Propriedade::orderBy('nome')->get();

        return view('admin.recursos.edit', compact('recurso', 'propriedades'));
    }

    public function update(Request $request, Recurso $recurso)
    {
        $validated = $request->validate([
            'propriedade_id' => 'required|exists:propriedades,id',
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:trator,implemento,pulverizador,colheitadeira,outro',
            'status' => 'required|in:disponivel,em_uso,manutencao',
        ]);

        $recurso->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Recurso atualizado com sucesso.',
                'redirect' => route('admin.recursos.show', $recurso),
            ]);
        }

        return redirect()->route('admin.recursos.show', $recurso)->with('success', 'Recurso atualizado com sucesso.');
    }

    public function destroy(Request $request, Recurso $recurso)
    {
        if (Tarefa::where('recurso_id', $recurso->id)->exists()) {
            return $request->wantsJson()
                ? response()->json(['message' => 'Este recurso está associado a tarefas e não pode ser excluído.'], 422)
                : back()->with('error', 'Este recurso está associado a tarefas e não pode ser excluído.');
        }
        $recurso->delete();
        return $request->wantsJson()
            ? response()->json(['message' => 'Recurso excluído com sucesso.'])
            : redirect()->route('admin.recursos.index')->with('success', 'Recurso excluído com sucesso.');
    }
}
