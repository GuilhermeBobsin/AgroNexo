<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Propriedade;
use App\Models\Recurso;
use Illuminate\Http\Request;

class RecursoController extends Controller
{
    public function index()
    {
        $recursos = Recurso::with('propriedade')->orderBy('nome')->paginate(15);
        $propriedades = Propriedade::orderBy('nome')->get();

        return view('admin.recursos.index', compact('recursos', 'propriedades'));
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
            return response()->json(['message' => 'Recurso cadastrado com sucesso.'], 201);
        }

        return redirect()->route('admin.recursos.index')->with('success', 'Recurso cadastrado com sucesso.');
    }
}
