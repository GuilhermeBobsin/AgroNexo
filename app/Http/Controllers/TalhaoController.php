<?php

namespace App\Http\Controllers;

use App\Models\Cultura;
use App\Models\Propriedade;
use App\Models\Talhao;
use Illuminate\Http\Request;

class TalhaoController extends Controller
{
    public function index(Propriedade $propriedade)
    {
        $talhoes = $propriedade->talhoes()
            ->with('cultura')
            ->orderBy('nome')
            ->get();

        return view(
            'admin.talhoes.index',
            compact('propriedade', 'talhoes')
        );
    }

    public function create(Propriedade $propriedade)
    {
        $culturas = Cultura::orderBy('nome')->get();

        return view(
            'admin.talhoes.create',
            compact('propriedade', 'culturas')
        );
    }

    public function store(Request $request, Propriedade $propriedade)
    {
        $limite = json_decode(
            $request->input('limite'),
            true
        );

        $request->merge([
            'limite' => $limite,
        ]);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',

            'cultura_id' => [
                'nullable',
                'exists:culturas,id',
            ],

            'area' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'limite' => [
                'required',
                'array',
                'min:3',
            ],
        ]);

        $talhao = $propriedade->talhoes()->create(
            $validated
        );

        if ($request->wantsJson()) {

            return response()->json([
                'message' => 'Talhão criado com sucesso.',
                'id' => $talhao->id,
            ], 201);
        }

        return redirect()
            ->route(
                'admin.propriedades.talhoes.index',
                $propriedade
            )
            ->with(
                'success',
                'Talhão criado com sucesso.'
            );
    }

    public function show(
        Propriedade $propriedade,
        Talhao $talhao
    ) {
        $talhao->load([
            'cultura',
            'aplicacoes',
            'alertas',
            'leiturasClimaticas',
        ]);

        return view(
            'admin.talhoes.show',
            compact('propriedade', 'talhao')
        );
    }

    public function edit(
        Propriedade $propriedade,
        Talhao $talhao
    ) {
        $culturas = Cultura::orderBy('nome')->get();

        return view(
            'admin.talhoes.edit',
            compact(
                'propriedade',
                'talhao',
                'culturas'
            )
        );
    }

    public function update(
        Request $request,
        Propriedade $propriedade,
        Talhao $talhao
    ) {
        $validated = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
            ],

            'cultura_id' => [
                'required',
                'exists:culturas,id',
            ],

            'area' => [
                'required',
                'numeric',
                'gt:0',
            ],

            'latitude' => [
                'required',
                'numeric',
                'between:-90,90',
            ],

            'longitude' => [
                'required',
                'numeric',
                'between:-180,180',
            ],
        ]);

        $talhao->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Talhão atualizado com sucesso.',
                'id' => $talhao->id,
            ]);
        }

        return redirect()
            ->route(
                'admin.propriedades.show',
                $propriedade
            )
            ->with(
                'success',
                'Talhão atualizado com sucesso.'
            );
    }

    public function destroy(
        Request $request,
        Propriedade $propriedade,
        Talhao $talhao
    ) {
        $talhao->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Talhão excluído com sucesso.',
            ]);
        }

        return redirect()
            ->route(
                'admin.propriedades.show',
                $propriedade
            )
            ->with(
                'success',
                'Talhão excluído com sucesso.'
            );
    }
}
