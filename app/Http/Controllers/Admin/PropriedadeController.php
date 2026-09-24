<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Propriedade;
use App\Models\Talhao;
use Illuminate\Http\Request;

class PropriedadeController extends Controller
{
    public function index()
    {
        $propriedades = Propriedade::orderBy('nome')->paginate(12);
        $contagem = $propriedades->total();
        $talhoes = Talhao::all()->count();
        $areaTotal = Talhao::sum('area');
        $quantidadeCulturas = Talhao::whereNotNull('cultura_id')->distinct('cultura_id')->count('cultura_id');

        return view('admin.propriedades.index', compact('propriedades', 'contagem', 'talhoes', 'areaTotal', 'quantidadeCulturas'));
    }

    public function create()
    {
        return view('admin.propriedades.create');
    }

    public function edit(Propriedade $propriedade)
    {
        return view('admin.propriedades.edit', compact('propriedade'));
    }

    public function update(Request $request, Propriedade $propriedade)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'localizacao' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);
        $propriedade->update($validated);
        return redirect()->route('admin.propriedades.show', $propriedade)->with('success', 'Propriedade atualizada com sucesso.');
    }

    public function destroy(Request $request, Propriedade $propriedade)
    {
        if ($propriedade->talhoes()->exists() || $propriedade->recursos()->exists() || $propriedade->produtos()->exists() || $propriedade->alertas()->exists() || $propriedade->tarefas()->exists()) {
            return back()->with('error', 'Esta propriedade tem talhões, recursos, estoque, tarefas ou alertas vinculados. Remova ou mova os registros dependentes antes de excluí-la.');
        }
        $propriedade->usuarios()->detach();
        $propriedade->delete();
        return redirect()->route('admin.propriedades.index')->with('success', 'Propriedade excluída com sucesso.');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'localizacao' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $propriedade = Propriedade::create($validated);

        $propriedade->usuarios()->attach(auth()->id(), ['papel' => 'dono']);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Propriedade criada com sucesso.',
                'id' => $propriedade->id,
            ], 201);
        }

        return redirect()->route('admin.propriedades.index')->with('success', 'Propriedade criada com sucesso.');
    }

    public function show(Propriedade $propriedade)
    {
        $propriedade->load([
            'talhoes.cultura',
            'usuarios',
        ]);

        return view('admin.propriedades.show', compact('propriedade'));
    }
}
