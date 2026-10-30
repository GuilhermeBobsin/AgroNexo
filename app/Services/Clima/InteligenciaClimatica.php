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
        $dadosRecentes = $this->dadosRecentes($porDia);
        $dias = [];

        for ($offset = 0; $offset < config('clima.dias_previsao', 7); $offset++) {
            $data = today()->addDays($offset);
            $horasDia = $porDia[$data->toDateString()] ?? collect();
            $periodoDiurnoEncerrado = $data->isToday()
                && now()->hour >= config('clima.pulverizacao.hora_fim', 18);
            $avaliacoes = [
                'pulverizacao' => $this->pulverizacao($data, $horasDia, null, $periodoDiurnoEncerrado),
                'aracao' => $this->aracao($data, $porDia),
                'calagem' => $this->calagem($data, $horasDia, null, $periodoDiurnoEncerrado),
            ];
            if (!$dadosRecentes) {
                foreach ($avaliacoes as $operacao => $resultado) {
                    $avaliacoes[$operacao] = $this->resultado('indisponivel', 'Dados climáticos desatualizados; aguarde sincronização bem-sucedida antes de usar esta orientação.');
                }
            }
            $dias[] = [
                'data' => $data->copy(),
                'condicoes' => $this->resumoCondicoes($horasDia),
                ...$avaliacoes,
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
        if (!$this->dadosRecentes($porDia)) {
            return $this->resultado('indisponivel', 'Dados climáticos ausentes ou desatualizados; aguarde sincronização bem-sucedida.');
        }

        return match ($operacao) {
            'pulverizacao' => $this->pulverizacao($data, $porDia[$data->toDateString()] ?? collect(), $hora),
            'aracao' => $this->aracao($data, $porDia),
            'calagem' => $this->calagem($data, $porDia[$data->toDateString()] ?? collect(), $hora),
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

    private function dadosRecentes(Collection $porDia): bool
    {
        $ultimaAtualizacao = $porDia->flatten(1)
            ->map(fn (PrevisaoClimatica $previsao) => $previsao->atualizado_em?->getTimestamp())
            ->filter()
            ->max();

        return $ultimaAtualizacao !== null
            && $ultimaAtualizacao >= now()->subHours(config('clima.idade_maxima_horas', 2))->getTimestamp();
    }

    private function pulverizacao(Carbon $data, Collection $horas, ?string $horaPrevista = null, bool $incluirHorasPassadas = false): array
    {
        $regra = config('clima.pulverizacao');
        $horasCampo = $horas->filter(function (PrevisaoClimatica $p) use ($regra, $horaPrevista, $incluirHorasPassadas) {
            $hora = (int) $p->previsto_para->format('G');
            if (!$incluirHorasPassadas && $p->previsto_para->isPast()) {
                return false;
            }
            if ($horaPrevista !== null) {
                return $p->previsto_para->format('H:i') === substr($horaPrevista, 0, 5);
            }
            return $hora >= $regra['hora_inicio'] && $hora < $regra['hora_fim'];
        });

        if ($horasCampo->isEmpty()) {
            return $this->resultado('indisponivel', 'Sem previsão horária disponível para avaliar a pulverização.');
        }

        if ($horaPrevista !== null) {
            return $this->avaliarPulverizacaoHora($horasCampo->first(), $horas, $regra);
        }

        $avaliacoes = $horasCampo->map(fn (PrevisaoClimatica $hora) => [
            'hora' => $hora,
            'resultado' => $this->avaliarPulverizacaoHora($hora, $horas, $regra),
        ]);
        $favoraveis = $avaliacoes->filter(fn (array $item) => $item['resultado']['status'] === 'indicado');
        $alertas = $avaliacoes->flatMap(fn (array $item) => $item['resultado']['alertas'] ?? [])->unique()->values()->all();

        if ($favoraveis->isNotEmpty()) {
            $janelas = $this->janelasSeguras($favoraveis->pluck('hora'));
            $misto = $favoraveis->count() < $avaliacoes->count();
            $mensagem = 'Janelas favoráveis para pulverização: '.implode(', ', $janelas).'.';
            if ($incluirHorasPassadas) {
                $mensagem = 'Resumo retrospectivo de hoje. '.$mensagem.' Essas janelas já passaram; confirme as condições atuais antes de programar uma operação.';
            }
            if ($misto) {
                $mensagem .= ' Fora dessas janelas, as condições variam; confira os alertas e evite os horários desfavoráveis.';
                $alertas[] = 'Há horários desfavoráveis ou de atenção fora das janelas destacadas.';
            }

            return $this->resultado('indicado', $mensagem, array_values(array_unique($alertas)));
        }

        $status = $avaliacoes->contains(fn (array $item) => $item['resultado']['status'] === 'nao_indicado')
            ? 'nao_indicado'
            : ($avaliacoes->contains(fn (array $item) => $item['resultado']['status'] === 'atencao') ? 'atencao' : 'indisponivel');

        return $this->resultado($status, 'Não há uma janela favorável identificada na previsão consultada. Revise os alertas e confirme as condições locais antes de decidir.', array_values(array_unique($alertas)));
    }

    private function avaliarPulverizacaoHora(PrevisaoClimatica $hora, Collection $horasDia, array $regra): array
    {
        if ($hora->velocidade_vento === null || $hora->umidade === null || $hora->temperatura === null) {
            return $this->resultado('indisponivel', 'Faltam dados horários de vento, umidade relativa ou temperatura para esta avaliação.');
        }

        $vento = (float) $hora->velocidade_vento;
        $umidade = (float) $hora->umidade;
        $temperatura = (float) $hora->temperatura;
        $alertas = [];
        $statusVento = 'indicado';
        if ($vento < $regra['vento_minimo_favoravel_kmh']) {
            $statusVento = 'atencao';
            $alertas[] = 'Vento abaixo de 3 km/h: atenção a inversão térmica e suspensão de gotas.';
        } elseif ($vento <= $regra['vento_maximo_favoravel_kmh']) {
            $alertas[] = 'Vento dentro da faixa favorável de 3 a 6,5 km/h.';
        } elseif ($vento <= $regra['vento_maximo_atencao_kmh']) {
            $statusVento = 'atencao';
            $alertas[] = 'Vento entre 6,5 e 9,5 km/h: atenção à deriva e à bula do produto.';
        } else {
            $statusVento = 'nao_indicado';
            $alertas[] = 'Vento acima de 9,5 km/h: risco elevado de deriva.';
        }

        $statusUmidade = 'indicado';
        if ($umidade < $regra['umidade_relativa_minima']) {
            $statusUmidade = 'nao_indicado';
            $alertas[] = 'Umidade relativa abaixo de 55%: a gota pode evaporar mais rapidamente.';
        } else {
            $alertas[] = 'Umidade relativa igual ou superior a 55%.';
        }

        $statusTemperatura = 'indicado';
        if ($temperatura > $regra['temperatura_maxima']) {
            $statusTemperatura = 'nao_indicado';
            $alertas[] = 'Temperatura acima de 30 °C: aumenta o risco de evaporação e volatilização.';
        }

        $inicioChuva = $hora->previsto_para;
        $fimChuva = $inicioChuva->copy()->addHours($regra['chuva_apos_aplicacao_horas']);
        $horasChuva = $horasDia->filter(fn (PrevisaoClimatica $p) =>
            $p->previsto_para->gte($inicioChuva) && $p->previsto_para->lte($fimChuva));
        $choveNaJanela = $horasChuva->contains(fn (PrevisaoClimatica $p) =>
            (float) ($p->precipitacao ?? 0) > 0
            || (float) ($p->chance_chuva ?? 0) >= $regra['chuva_probabilidade_alerta']);
        $chuvaSemDados = $horasChuva->contains(fn (PrevisaoClimatica $p) => $p->precipitacao === null);
        if ($choveNaJanela) {
            $alertas[] = 'Há chuva prevista durante ou nas horas seguintes à aplicação; consulte o intervalo sem chuva indicado na bula.';
        }
        if ($chuvaSemDados) {
            $alertas[] = 'A previsão de precipitação está incompleta para parte do intervalo analisado.';
        }

        $status = $this->combinarStatus([$statusVento, $statusUmidade, $statusTemperatura]);
        if (($choveNaJanela || $chuvaSemDados) && $status === 'indicado') {
            $status = 'atencao';
        }
        $mensagem = sprintf('Às %s: vento %.1f km/h, umidade relativa %.0f%% e temperatura %.1f °C.',
            $hora->previsto_para->format('H:i'), $vento, $umidade, $temperatura);

        return $this->resultado($status, $mensagem, $alertas);
    }

    private function aracao(Carbon $data, Collection $porDia): array
    {
        $regra = config('clima.aracao');
        $horasDia = ($porDia->get($data->toDateString(), collect()))
            ->filter(fn (PrevisaoClimatica $p) => !$p->previsto_para->isPast());
        $umidades = $horasDia->pluck('umidade_solo')->filter(fn ($v) => $v !== null)->map(fn ($v) => (float) $v);
        if ($umidades->isEmpty()) {
            return $this->resultado('indisponivel', 'A Open-Meteo não forneceu umidade estimada para o dia. Verifique o solo diretamente no talhão.');
        }

        $umidade = $umidades->avg();
        $alertas = [];
        $status = 'indicado';
        if ($umidade < $regra['umidade_solo_friavel_minima']) {
            $status = 'atencao';
            $alertas[] = 'Estimativa de solo seco: pode haver torrões, maior esforço de tração e desgaste dos implementos.';
        } elseif ($umidade > $regra['umidade_solo_friavel_maxima']) {
            $status = 'atencao';
            $alertas[] = 'Estimativa de solo úmido: confirme trafegabilidade e risco de compactação antes de entrar com máquinas.';
        } else {
            $alertas[] = 'A estimativa superficial está dentro da faixa provisória usada como referência de friabilidade.';
        }

        if ($umidades->count() < 4) {
            $status = $this->combinarStatus([$status, 'atencao']);
            $alertas[] = 'Há poucas amostras horárias de umidade para representar o dia.';
        }

        $chuvaHoje = $horasDia->sum(fn (PrevisaoClimatica $p) => (float) ($p->precipitacao ?? 0));
        $chanceChuva = $horasDia->max('chance_chuva');
        if ($chuvaHoje > 0 || (float) ($chanceChuva ?? 0) >= $regra['chuva_probabilidade_alerta']) {
            $alertas[] = 'Há chuva ou probabilidade de chuva na previsão; reavalie a umidade e o tráfego no momento da operação.';
        }

        $mensagem = sprintf('Umidade volumétrica estimada pelo modelo (3–9 cm): %.3f m³/m³. A faixa de friabilidade depende do tipo e da estrutura do solo; faça o teste do torrão no campo.', $umidade);

        return $this->resultado($status, $mensagem, $alertas);
    }

    private function calagem(Carbon $data, Collection $horas, ?string $horaPrevista = null, bool $incluirHorasPassadas = false): array
    {
        $regra = config('clima.calagem');
        $horasDia = $horas->filter(fn (PrevisaoClimatica $p) =>
            ($incluirHorasPassadas || !$p->previsto_para->isPast())
            && (int) $p->previsto_para->format('G') >= 6
            && (int) $p->previsto_para->format('G') < 18
            && ($horaPrevista === null || $p->previsto_para->format('H:i') === substr($horaPrevista, 0, 5)));
        if ($horasDia->isEmpty()) {
            return $this->resultado('indisponivel', 'Sem previsão horária para avaliar a calagem.');
        }
        $comVento = $horasDia->filter(fn (PrevisaoClimatica $p) => $p->velocidade_vento !== null);
        if ($comVento->isEmpty()) {
            return $this->resultado('indisponivel', 'Sem dados de vento para avaliar a distribuição do calcário.');
        }

        $classificacoes = $comVento->map(function (PrevisaoClimatica $p) use ($regra) {
            $vento = (float) $p->velocidade_vento;
            $status = match (true) {
                $vento < $regra['vento_maximo_favoravel_kmh'] => 'indicado',
                $vento <= $regra['vento_maximo_atencao_kmh'] => 'atencao',
                $vento <= $regra['vento_maximo_desfavoravel_kmh'] => 'nao_indicado',
                default => 'nao_indicado',
            };

            return ['hora' => $p, 'status' => $status, 'vento' => $vento];
        });
        $favoraveis = $classificacoes->where('status', 'indicado');
        $alertas = [];
        $dadosFaltantes = $classificacoes->count() < $horasDia->count();
        if ($dadosFaltantes) {
            $alertas[] = 'Parte da previsão não contém velocidade do vento.';
        }
        foreach ($classificacoes as $classificacao) {
            if ($classificacao['status'] !== 'indicado') {
                $alertas[] = $classificacao['hora']->previsto_para->format('H:i').': '.$this->mensagemVentoCalagem($classificacao['vento'], $regra);
            }
        }

        $chuva = $horasDia->filter(fn (PrevisaoClimatica $p) =>
            (float) ($p->precipitacao ?? 0) > 0
            || (float) ($p->chance_chuva ?? 0) >= $regra['chuva_probabilidade_alerta']
            || in_array((int) $p->codigo_tempo, [95, 96, 99], true));
        if ($chuva->isNotEmpty()) {
            $alertas[] = 'Há precipitação, chance elevada de chuva ou trovoada na previsão; avalie o risco de perda e a forma de aplicação.';
        }

        if ($horaPrevista !== null) {
            $avaliacao = $classificacoes->first();
            $alertas[] = $this->mensagemVentoCalagem($avaliacao['vento'], $regra);
            if ($chuva->isNotEmpty()) {
                return $this->resultado($this->combinarStatus([$avaliacao['status'], 'atencao']), sprintf('Às %s, vento previsto de %.1f km/h.', $avaliacao['hora']->previsto_para->format('H:i'), $avaliacao['vento']), $alertas);
            }

            return $this->resultado($avaliacao['status'], sprintf('Às %s, vento previsto de %.1f km/h.', $avaliacao['hora']->previsto_para->format('H:i'), $avaliacao['vento']), $alertas);
        }

        if ($favoraveis->isNotEmpty()) {
            $janelas = $this->janelasSeguras($favoraveis->pluck('hora'));
            $misto = $favoraveis->count() < $classificacoes->count() || $chuva->isNotEmpty() || $dadosFaltantes;
            if ($misto) {
                $alertas[] = 'As condições variam durante o dia; priorize as janelas destacadas e confirme o vento no local.';
            }

            $mensagem = 'Janelas com vento favorável para calagem: '.implode(', ', $janelas).'.';
            if ($incluirHorasPassadas) {
                $mensagem = 'Resumo retrospectivo de hoje. '.$mensagem.' Essas janelas já passaram; confirme as condições atuais antes de programar uma operação.';
            }

            return $this->resultado('indicado', $mensagem, $alertas);
        }

        $ventoMax = (float) $classificacoes->max('vento');
        $status = $this->combinarStatus($classificacoes->pluck('status')->all());
        $mensagem = $ventoMax > $regra['vento_maximo_desfavoravel_kmh']
            ? sprintf('Vento acima de 30 km/h (máximo previsto %.1f km/h): risco de desperdício elevado. A decisão operacional continua com o responsável.', $ventoMax)
            : sprintf('Não há janela de vento favorável identificada; máximo previsto %.1f km/h.', $ventoMax);

        return $this->resultado($status, $mensagem, $alertas);
    }

    private function mensagemVentoCalagem(float $vento, array $regra): string
    {
        return match (true) {
            $vento < $regra['vento_maximo_favoravel_kmh'] => 'Abaixo de 10 km/h: faixa favorável para distribuição uniforme.',
            $vento <= $regra['vento_maximo_atencao_kmh'] => 'De 10 a 15 km/h: atenção para desvio leve do calcário.',
            $vento <= $regra['vento_maximo_desfavoravel_kmh'] => 'Acima de 15 até 30 km/h: desfavorável por risco de deriva.',
            default => 'Acima de 30 km/h: risco de desperdício elevado (condição classificada como proibitiva).',
        };
    }

    private function combinarStatus(array $statuses): string
    {
        if (in_array('nao_indicado', $statuses, true)) return 'nao_indicado';
        if (in_array('atencao', $statuses, true)) return 'atencao';
        if (in_array('indisponivel', $statuses, true)) return 'indisponivel';

        return 'indicado';
    }

    private function resultado(string $status, string $mensagem, array $alertas = []): array
    {
        return ['status' => $status, 'mensagem' => $mensagem, 'alertas' => array_values(array_unique($alertas))];
    }

    private function resumoCondicoes(Collection $horas): array
    {
        $valores = fn (string $campo) => $horas->pluck($campo)->filter(fn ($valor) => $valor !== null)->map(fn ($valor) => (float) $valor);

        return [
            'vento_maximo' => $valores('velocidade_vento')->max(),
            'rajada_maxima' => $valores('rajada_vento')->max(),
            'precipitacao' => $valores('precipitacao')->sum(),
            'chance_chuva_maxima' => $valores('chance_chuva')->max(),
            'temperatura_maxima' => $valores('temperatura')->max(),
            'umidade_relativa_minima' => $valores('umidade')->min(),
            'umidade_solo_media' => $valores('umidade_solo')->avg(),
            'evapotranspiracao' => $valores('evapotranspiracao')->sum(),
            'horas_sol' => $valores('duracao_sol')->sum() / 3600,
            'disponivel' => $horas->isNotEmpty(),
        ];
    }

    private function horasDiurnasEsperadas(Carbon $data, int $inicio, int $fim): int
    {
        $primeiraHora = $data->isToday() ? max($inicio, now()->hour + 1) : $inicio;

        return max(0, $fim - $primeiraHora);
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
