<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Propriedade;
use Illuminate\Http\Request;

class PropriedadeController extends Controller
{
    public function index()
    {
        $propriedades = Propriedade::all();
        $contagem = $propriedades->count();
        $areaTotal = $propriedades->sum('area');
        return view('admin.propriedades.index', compact('propriedades', 'contagem', 'areaTotal'));
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
}
