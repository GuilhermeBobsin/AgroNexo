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
        $contagem = $propriedades->count();
        $talhoes = Talhao::all()->count();
        $areaTotal = Talhao::sum('area');
        $quantidadeCulturas = Talhao::whereNotNull('cultura_id')->distinct('cultura_id')->count('cultura_id');

        return view('admin.propriedades.index', compact('propriedades', 'contagem', 'talhoes', 'areaTotal', 'quantidadeCulturas'));
    }

    public function create()
    {
        return view('admin.propriedades.create');
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
