<?php

namespace App\Services\Clima;

use App\Models\PrevisaoClimatica;
use App\Models\Talhao;
use App\Models\Tarefa;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class InteligenciaClimatica
{
    public function semana(Talhao $talhao): array
    {
        $inicio = today()->subDays(config('clima.dias_historico', 7));
        $fim = today()->addDays(config('clima.dias_previsao', 7));
        $porDia = $this->previsoes($talhao, $inicio, $fim);
        $dias = [];

        for ($offset = 0; $offset < config('clima.dias_previsao', 7); $offset++) {
            $data = today()->addDays($offset);
            $dias[] = [
                'data' => $data->copy(),
                'pulverizacao' => $this->pulverizacao($data, $porDia[$data->toDateString()] ?? collect()),
                'aracao' => $this->aracao($data, $porDia),
                'calagem' => $this->calagem($data, $porDia[$data->toDateString()] ?? collect()),
            ];
        }

        return $dias;
    }

    public function orientacaoParaTarefa(Tarefa $tarefa): ?array
    {
        $operacao = match ($tarefa->tipo) {
            'aplicacao' => 'pulverizacao',
            'aracao' => 'aracao',
            'calagem' => 'calagem',
            default => null,
        };
        if (!$operacao) {
            return null;
        }
        if (!$tarefa->talhao_id) {
            return $this->resultado('indisponivel', 'Associe um talhão à tarefa para avaliar as condições locais.');
        }

        $data = Carbon::parse($tarefa->data_prevista->format('Y-m-d'), config('app.timezone'));
        if ($data->lt(today())) {
            return $this->resultado('indisponivel', 'A data prevista já passou; confirme manualmente as condições atuais.');
        }
        $hora = $tarefa->hora_prevista;
        if ($data->isToday() && $hora && Carbon::parse($data->format('Y-m-d').' '.$hora, config('app.timezone'))->isPast()) {
            $hora = null;
        }

        return $this->avaliarTipoDia($operacao, $tarefa->talhao, $data->format('Y-m-d'), $hora);
    }

    public function avaliarTipoDia(string $tipo, Talhao $talhao, string $data, ?string $hora = null): ?array
    {
        $operacao = match ($tipo) {
            'aplicacao' => 'pulverizacao',
            'pulverizacao' => 'pulverizacao',
            'aracao' => 'aracao',
            'calagem' => 'calagem',
            default => null,
        };
        if (!$operacao) {
            return null;
        }

        $data = Carbon::parse($data, config('app.timezone'));
        $inicio = $data->copy()->subDays(config('clima.dias_historico', 7));
        $porDia = $this->previsoes($talhao, $inicio, $data->copy()->addDay());

        return match ($operacao) {
            'pulverizacao' => $this->pulverizacao($data, $porDia[$data->toDateString()] ?? collect(), $hora),
            'aracao' => $this->aracao($data, $porDia),
            'calagem' => $this->calagem($data, $porDia[$data->toDateString()] ?? collect()),
        };
    }

    private function previsoes(Talhao $talhao, Carbon $inicio, Carbon $fim): Collection
    {
        return PrevisaoClimatica::where('talhao_id', $talhao->id)
            ->whereBetween('previsto_para', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()])
            ->orderBy('previsto_para')
            ->get()
            ->groupBy(fn (PrevisaoClimatica $p) => $p->previsto_para->toDateString());
    }

    private function pulverizacao(Carbon $data, Collection $horas, ?string $horaPrevista = null): array
    {
        $regra = config('clima.pulverizacao');
        $horasCampo = $horas->filter(function (PrevisaoClimatica $p) use ($regra, $horaPrevista) {
            $hora = (int) $p->previsto_para->format('G');
            if ($p->previsto_para->isPast()) {
                return false;
            }
            if ($horaPrevista !== null) {
                return $p->previsto_para->format('H:i') === substr($horaPrevista, 0, 5);
            }
            return $hora >= $regra['hora_inicio'] && $hora < $regra['hora_fim'];
        })->filter(fn (PrevisaoClimatica $p) => $p->velocidade_vento !== null);

        if ($horasCampo->isEmpty()) {
            return $this->resultado('indisponivel', 'Sem dados de vento para esse horário.');
        }

        $seguras = $horasCampo->filter(fn (PrevisaoClimatica $p) =>
            (float) $p->velocidade_vento >= $regra['vento_minimo_kmh']
            && (float) $p->velocidade_vento <= $regra['vento_maximo_kmh']);

        if ($seguras->isEmpty()) {
            $minimo = (float) $horasCampo->min('velocidade_vento');
            $maximo = (float) $horasCampo->max('velocidade_vento');
            $causa = $minimo < $regra['vento_minimo_kmh']
                ? 'vento abaixo de 3 km/h pode favorecer inversão térmica'
                : 'vento acima de 10 km/h aumenta o risco de deriva';
            return $this->resultado('nao_indicado', "Pulverização não indicada: {$causa}. Vento previsto entre {$minimo} e {$maximo} km/h.");
        }

        if ($horaPrevista !== null) {
            $vento = (float) $seguras->first()->velocidade_vento;
            return $this->resultado('indicado', "Pulverização indicada no horário previsto; vento de {$vento} km/h, dentro da faixa de 3 a 10 km/h.");
        }

        $janelas = $this->janelasSeguras($seguras);
        return $this->resultado('indicado', 'Pulverização indicada nas janelas: '.implode(', ', $janelas).'.');
    }

    private function aracao(Carbon $data, Collection $porDia): array
    {
        $regra = config('clima.aracao');
        $diasSecos = 0;
        $diasChuvosos = 0;
        $diasSolares = 0;
        $sequenciaSecaAberta = true;
        $sequenciaChuvosaAberta = true;
        $sequenciaEnsolaradaAberta = true;
        $solo = null;

        $diasAnalise = max($regra['dias_secos_alerta'], $regra['dias_chuvosos_alerta'], $regra['dias_sol_apos_chuva']);
        for ($offset = 1; $offset <= $diasAnalise; $offset++) {
            $dia = $data->copy()->subDays($offset)->toDateString();
            $horas = $porDia->get($dia, collect());
            if ($horas->isEmpty()) {
                if ($offset <= $regra['dias_sol_apos_chuva']) {
                    return $this->resultado('indisponivel', 'Sem histórico meteorológico suficiente para confirmar os três dias de sol após a chuva.');
                }
                break;
            }
            $chuva = $horas->sum(fn (PrevisaoClimatica $p) => (float) ($p->precipitacao ?? 0));
            $sol = $horas->sum(fn (PrevisaoClimatica $p) => (float) ($p->duracao_sol ?? 0));
            if ($offset === 1) {
                $solo = $horas->last()?->umidade_solo;
            }
            $diaSeco = $chuva <= $regra['precipitacao_dia_seco_mm'];
            $diaChuvoso = !$diaSeco;
            $diaEnsolarado = $diaSeco && $sol >= $regra['sol_minimo_segundos_dia'];

            if ($sequenciaSecaAberta) {
                if ($diaSeco) {
                    $diasSecos++;
                } else {
                    $sequenciaSecaAberta = false;
                }
            }
            if ($sequenciaChuvosaAberta) {
                if ($diaChuvoso) {
                    $diasChuvosos++;
                } else {
                    $sequenciaChuvosaAberta = false;
                }
            }
            if ($sequenciaEnsolaradaAberta) {
                if ($diaEnsolarado) {
                    $diasSolares++;
                } else {
                    $sequenciaEnsolaradaAberta = false;
                }
            }
        }

        if ($diasChuvosos >= $regra['dias_chuvosos_alerta']) {
            return $this->resultado('nao_indicado', "Aração não indicada: há {$diasChuvosos} dias consecutivos com chuva; aguarde a drenagem do solo.");
        }
        if ($diasSecos >= $regra['dias_secos_alerta'] && $diasSolares >= $regra['dias_secos_alerta']
            && ($solo === null || (float) $solo < $regra['umidade_solo_muito_seco'])) {
            return $this->resultado('nao_indicado', 'Aração não indicada: há uma sequência prolongada de dias ensolarados e a umidade estimada sugere solo muito seco.');
        }
        if ($diasSolares < $regra['dias_sol_apos_chuva']) {
            return $this->resultado('atencao', "Aguarde {$regra['dias_sol_apos_chuva']} dias de sol após a chuva antes de aração. Dias ensolarados consecutivos: {$diasSolares}.");
        }

        $horasHoje = $porDia->get($data->toDateString(), collect());
        $chuvaHoje = $horasHoje
            ->sum(fn (PrevisaoClimatica $p) => (float) ($p->precipitacao ?? 0));
        if ($chuvaHoje > $regra['precipitacao_dia_seco_mm'] && $diasChuvosos >= $regra['dias_chuvosos_alerta'] - 1) {
            return $this->resultado('nao_indicado', 'Aração não indicada: o período chuvoso deve continuar no dia programado.');
        }
        if ($chuvaHoje >= $regra['chuva_forte_dia_mm']) {
            return $this->resultado('nao_indicado', 'Aração não indicada: há previsão de chuva forte no dia programado.');
        }

        return $this->resultado('indicado', 'Aração indicada: há pelo menos três dias ensolarados e sem chuva desde a última chuva relevante.');
    }

    private function calagem(Carbon $data, Collection $horas): array
    {
        $regra = config('clima.calagem');
        $horasDia = $horas->filter(fn (PrevisaoClimatica $p) =>
            !$p->previsto_para->isPast()
            && (int) $p->previsto_para->format('G') >= 6
            && (int) $p->previsto_para->format('G') < 18);
        $horas = $horas->filter(fn (PrevisaoClimatica $p) => !$p->previsto_para->isPast());
        if ($horasDia->isEmpty()) {
            return $this->resultado('indisponivel', 'Sem previsão horária para avaliar a calagem.');
        }

        $ventos = $horasDia->pluck('velocidade_vento')->filter(fn ($v) => $v !== null)->map(fn ($v) => (float) $v);
        if ($ventos->isEmpty()) {
            return $this->resultado('indisponivel', 'Sem dados de vento para avaliar a calagem.');
        }
        $ventoMax = $ventos->max();
        if ($ventoMax > $regra['vento_maximo_kmh']) {
            return $this->resultado('nao_indicado', "Calagem não indicada: vento previsto de até {$ventoMax} km/h pode causar deriva; limite operacional adotado: {$regra['vento_maximo_kmh']} km/h.");
        }

        $chuvaDia = $horas->sum(fn (PrevisaoClimatica $p) => (float) ($p->precipitacao ?? 0));
        $chuvaHoraMax = $horas->max('precipitacao');
        $trovoada = $horas->contains(fn (PrevisaoClimatica $p) => in_array((int) $p->codigo_tempo, [95, 96, 99], true));
        if ($chuvaDia >= $regra['chuva_forte_dia_mm'] || (float) $chuvaHoraMax >= $regra['chuva_forte_hora_mm'] || $trovoada) {
            return $this->resultado('nao_indicado', 'Calagem não indicada: há previsão de chuva intensa ou trovoada, com risco de enxurrada e perda do calcário.');
        }

        $umidades = $horasDia->pluck('umidade_solo')->filter(fn ($v) => $v !== null)->map(fn ($v) => (float) $v);
        if ($umidades->isEmpty()) {
            return $this->resultado('indisponivel', 'A API não forneceu umidade estimada do solo; confirme a umidade no talhão.');
        }
        $umidade = $umidades->avg();
        if ($umidade < $regra['umidade_solo_minima']) {
            return $this->resultado('atencao', 'Calagem adiada: o modelo estima solo seco; confirme a condição no talhão e aguarde umidade moderada.');
        }
        if ($umidade > $regra['umidade_solo_maxima']) {
            return $this->resultado('atencao', 'Calagem adiada: o modelo estima solo muito úmido; confirme a condição e evite aplicação antes de enxurradas.');
        }

        return $this->resultado('indicado', 'Calagem indicada: vento fraco, umidade estimada do solo moderada e sem previsão de chuva intensa.');
    }

    private function resultado(string $status, string $mensagem): array
    {
        return ['status' => $status, 'mensagem' => $mensagem];
    }

    private function janelasSeguras(Collection $horas): array
    {
        $janelas = [];
        $inicio = null;
        $fim = null;
        $anterior = null;
        foreach ($horas->sortBy('previsto_para') as $hora) {
            $horaAtual = $hora->previsto_para->format('H:i');
            if ($anterior === null || $hora->previsto_para->diffInHours($anterior) > 1) {
                if ($inicio !== null) {
                    $janelas[] = "{$inicio}–{$fim}";
                }
                $inicio = $horaAtual;
            }
            $fim = $hora->previsto_para->copy()->addHour()->format('H:i');
            $anterior = $hora->previsto_para;
        }
        if ($inicio !== null) {
            $janelas[] = "{$inicio}–{$fim}";
        }

        return $janelas;
    }
}
