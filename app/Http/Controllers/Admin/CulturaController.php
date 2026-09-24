<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cultura;
use App\Models\Talhao;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CulturaController extends Controller
{
    public function index()
    {
        $culturas = Cultura::withCount('talhoes')->orderBy('nome')->paginate(12);
        $culturaMaisUsada = Cultura::withCount('talhoes')->orderByDesc('talhoes_count')->first();
        $talhoesSemCultura = Talhao::whereNull('cultura_id')->count();

        return view('admin.culturas.index', compact('culturas', 'culturaMaisUsada', 'talhoesSemCultura'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:culturas,nome',
        ]);

        Cultura::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Cultura criada com sucesso.'], 201);
        }

        return redirect()->route('admin.culturas.index')->with('success', 'Cultura criada com sucesso.');
    }

    public function update(Request $request, Cultura $cultura)
    {
        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255', Rule::unique('culturas', 'nome')->ignore($cultura->id)],
        ]);

        $cultura->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Cultura atualizada com sucesso.']);
        }

        return redirect()->route('admin.culturas.index')->with('success', 'Cultura atualizada com sucesso.');
    }

    public function destroy(Request $request, Cultura $cultura)
    {
        $cultura->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Cultura excluída com sucesso.']);
        }

        return redirect()->route('admin.culturas.index')->with('success', 'Cultura excluída com sucesso.');
    }
}
