<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cultura;
use App\Models\Propriedade;
use App\Models\Talhao;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TalhaoController extends Controller
{
    public function index(Propriedade $propriedade)
    {
        $talhoes = $propriedade->talhoes()->with('cultura')->orderBy('nome')->paginate(15);

        return view('admin.talhoes.index', compact('propriedade', 'talhoes'));
    }

    public function create(Propriedade $propriedade)
    {
        $culturas = Cultura::orderBy('nome')->get();

        return view('admin.talhoes.create', compact('propriedade', 'culturas'));
    }

    public function store(Request $request, Propriedade $propriedade)
    {
        $talhao = $propriedade->talhoes()->create($this->validarDados($request));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Talhão criado com sucesso.', 'id' => $talhao->id], 201);
        }

        return redirect()->route('admin.propriedades.talhoes.show', [$propriedade, $talhao])
            ->with('success', 'Talhão criado com sucesso.');
    }

    public function show(Propriedade $propriedade, Talhao $talhao)
    {
        $talhao = $propriedade->talhoes()->with(['cultura', 'aplicacoes.produto'])->findOrFail($talhao->id);

        return view('admin.talhoes.show', compact('propriedade', 'talhao'));
    }

    public function edit(Propriedade $propriedade, Talhao $talhao)
    {
        $talhao = $propriedade->talhoes()->findOrFail($talhao->id);
        $culturas = Cultura::orderBy('nome')->get();

        return view('admin.talhoes.edit', compact('propriedade', 'talhao', 'culturas'));
    }

    public function update(Request $request, Propriedade $propriedade, Talhao $talhao)
    {
        $talhao = $propriedade->talhoes()->findOrFail($talhao->id);
        $talhao->update($this->validarDados($request));

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Talhão atualizado com sucesso.', 'id' => $talhao->id]);
        }

        return redirect()->route('admin.propriedades.talhoes.show', [$propriedade, $talhao])
            ->with('success', 'Talhão atualizado com sucesso.');
    }

    public function destroy(Request $request, Propriedade $propriedade, Talhao $talhao)
    {
        $talhao = $propriedade->talhoes()->findOrFail($talhao->id);
        if ($talhao->aplicacoes()->exists() || $talhao->tarefas()->exists() || $talhao->previsoesClimaticas()->exists()
            || $talhao->alertas()->exists() || $talhao->leiturasClimaticas()->exists()) {
            $message = 'Este talhão possui histórico operacional ou climático e não pode ser excluído.';
            if ($request->wantsJson()) {
                return response()->json(['message' => $message], 422);
            }

            return redirect()->route('admin.propriedades.talhoes.index', $propriedade)->with('error', $message);
        }
        $talhao->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Talhão excluído com sucesso.']);
        }

        return redirect()->route('admin.propriedades.talhoes.index', $propriedade)
            ->with('success', 'Talhão excluído com sucesso.');
    }

    private function validarDados(Request $request): array
    {
        $limite = json_decode((string) $request->input('limite'), true);
        $request->merge(['limite' => $limite]);

        $validated = $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'cultura_id' => ['nullable', 'integer', Rule::exists('culturas', 'id')],
            'limite' => ['required', 'array', 'min:3'],
            'limite.*' => ['required', 'array', 'size:2'],
            'limite.*.0' => ['required', 'numeric', 'between:-90,90'],
            'limite.*.1' => ['required', 'numeric', 'between:-180,180'],
        ]);

        $validated['area'] = $this->areaEmHectares($validated['limite']);

        return $validated;
    }

    private function areaEmHectares(array $limite): float
    {
        $soma = 0.0;
        $pontos = count($limite);
        for ($i = 0; $i < $pontos; $i++) {
            $proximo = ($i + 1) % $pontos;
            $latitudeAtual = deg2rad((float) $limite[$i][0]);
            $latitudeProxima = deg2rad((float) $limite[$proximo][0]);
            $longitudeAtual = deg2rad((float) $limite[$i][1]);
            $longitudeProxima = deg2rad((float) $limite[$proximo][1]);
            $soma += ($longitudeProxima - $longitudeAtual) * (2 + sin($latitudeAtual) + sin($latitudeProxima));
        }

        $metrosQuadrados = abs($soma) * (6371008.8 ** 2) / 2;

        return round($metrosQuadrados / 10000, 2);
    }
}
