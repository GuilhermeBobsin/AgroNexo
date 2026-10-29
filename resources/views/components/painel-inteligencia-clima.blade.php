@php
    $classesClima = ['indicado' => 'success', 'atencao' => 'warning', 'nao_indicado' => 'danger', 'indisponivel' => 'secondary'];
    $rotulosClima = ['indicado' => 'Indicado', 'atencao' => 'Atenção', 'nao_indicado' => 'Não indicado', 'indisponivel' => 'Sem dados'];
    $operacoesClima = ['pulverizacao' => 'Pulverização', 'aracao' => 'Aração', 'calagem' => 'Calagem'];
    $resumoHoje = ['indicado' => 0, 'restricao' => 0, 'indisponivel' => 0];
    foreach ($locais as $localResumo) {
        foreach (($localResumo['dias'][0] ?? []) as $chave => $avaliacaoResumo) {
            if (!isset($operacoesClima[$chave]) || !is_array($avaliacaoResumo)) continue;
            if ($avaliacaoResumo['status'] === 'indicado') $resumoHoje['indicado']++;
            elseif (in_array($avaliacaoResumo['status'], ['atencao', 'nao_indicado'], true)) $resumoHoje['restricao']++;
            elseif ($avaliacaoResumo['status'] === 'indisponivel') $resumoHoje['indisponivel']++;
        }
    }
@endphp

@if (count($locais))
<section class="mb-4" aria-labelledby="climate-summary-title">
    <div class="d-flex align-items-baseline justify-content-between gap-2 mb-2">
        <h2 id="climate-summary-title" class="h3 mb-0">Resumo de hoje</h2>
        <span class="text-secondary small">Uma avaliação por operação em cada talhão</span>
    </div>
    <div class="row row-cards">
        <div class="col-6 col-xl-3"><div class="card card-sm"><div class="card-body"><div class="subheader">Talhões monitorados</div><div class="h1 mb-0">{{ count($locais) }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card card-sm"><div class="card-body"><div class="subheader">Operações indicadas</div><div class="h1 mb-0 text-success">{{ $resumoHoje['indicado'] }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card card-sm"><div class="card-body"><div class="subheader">Atenções e restrições</div><div class="h1 mb-0 text-warning">{{ $resumoHoje['restricao'] }}</div></div></div></div>
        <div class="col-6 col-xl-3"><div class="card card-sm"><div class="card-body"><div class="subheader">Avaliações sem dados</div><div class="h1 mb-0 text-secondary">{{ $resumoHoje['indisponivel'] }}</div></div></div></div>
    </div>
</section>
@endif

@forelse ($locais as $local)
    @php
        $atualizacao = $local['atualizado_em'] ? \Illuminate\Support\Carbon::parse($local['atualizado_em']) : null;
        $desatualizado = !$atualizacao || $atualizacao->lt(now()->subHours(config('clima.idade_maxima_horas', 2)));
    @endphp
    <section class="card mb-4 climate-location">
        <div class="card-header d-flex align-items-center justify-content-between gap-3 flex-wrap">
            <div>
                <h2 class="card-title mb-1">{{ $local['propriedade']->nome }} · {{ $local['talhao']->nome }}</h2>
                <div class="text-secondary small">{{ $local['talhao']->area ? number_format((float) $local['talhao']->area, 2, ',', '.') . ' ha' : 'Área não informada' }} · Coordenadas da propriedade: {{ $local['propriedade']->latitude }}, {{ $local['propriedade']->longitude }}</div>
            </div>
            <div class="text-end">
                <span class="badge bg-{{ $desatualizado ? 'warning' : 'success' }}-lt">{{ $desatualizado ? 'Dados desatualizados' : 'Dados recentes' }}</span>
                <div class="small text-secondary mt-1">{{ $atualizacao ? 'Atualizado ' . $atualizacao->diffForHumans() : 'Aguardando primeira sincronização' }}</div>
            </div>
        </div>
        <div class="card-body p-3 p-lg-4">
            <div class="row g-3">
                @foreach ($local['dias'] as $dia)
                    @php($condicoes = $dia['condicoes'])
                    <div class="col-12 col-xl-6">
                        <article class="card card-sm climate-day h-100">
                            <div class="card-header py-2">
                                <h3 class="card-title">{{ ['Sun'=>'Dom','Mon'=>'Seg','Tue'=>'Ter','Wed'=>'Qua','Thu'=>'Qui','Fri'=>'Sex','Sat'=>'Sáb'][$dia['data']->format('D')] }}, {{ $dia['data']->format('d/m') }}</h3>
                            </div>
                            <div class="card-body">
                                @if ($condicoes['disponivel'])
                                    <div class="climate-metrics mb-3" aria-label="Resumo meteorológico do dia">
                                        <div><span>Vento máx.</span><strong>{{ $condicoes['vento_maximo'] !== null ? number_format($condicoes['vento_maximo'], 1, ',', '.') . ' km/h' : '—' }}</strong></div>
                                        <div><span>Rajada máx.</span><strong>{{ $condicoes['rajada_maxima'] !== null ? number_format($condicoes['rajada_maxima'], 1, ',', '.') . ' km/h' : '—' }}</strong></div>
                                        <div><span>Chuva</span><strong>{{ number_format($condicoes['precipitacao'], 1, ',', '.') }} mm</strong></div>
                                        <div><span>Chance máx.</span><strong>{{ $condicoes['chance_chuva_maxima'] !== null ? number_format($condicoes['chance_chuva_maxima'], 0, ',', '.') . '%' : '—' }}</strong></div>
                                        <div><span>Umidade solo (3–9 cm)*</span><strong>{{ $condicoes['umidade_solo_media'] !== null ? number_format($condicoes['umidade_solo_media'] * 100, 0, ',', '.') . '%' : '—' }}</strong></div>
                                        <div><span>ET₀</span><strong>{{ $condicoes['evapotranspiracao'] !== null ? number_format($condicoes['evapotranspiracao'], 1, ',', '.') . ' mm' : '—' }}</strong></div>
                                    </div>
                                @else
                                    <div class="alert alert-warning py-2 mb-3">Ainda não há dados climáticos para este dia. As recomendações ficam indisponíveis até a próxima sincronização bem-sucedida.</div>
                                @endif
                                <div class="climate-recommendations">
                                    @foreach ($operacoesClima as $operacao => $nome)
                                        @php($avaliacao = $dia[$operacao])
                                        <div class="climate-recommendation">
                                            <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                                <span class="fw-semibold">{{ $nome }}</span>
                                                <span class="badge bg-{{ $classesClima[$avaliacao['status']] ?? 'secondary' }}-lt">{{ $rotulosClima[$avaliacao['status']] ?? 'Orientação' }}</span>
                                            </div>
                                            <p class="text-secondary small mb-0">{{ $avaliacao['mensagem'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
            <p class="small text-secondary mt-3 mb-0">Dados meteorológicos: <a href="https://open-meteo.com/" target="_blank" rel="noopener">Open-Meteo</a>, licenciados sob <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank" rel="noopener">CC BY 4.0</a> (dados agregados e interpretados pelo AgroNexo). *Umidade volumétrica estimada pelo modelo para 3–9 cm. Previsões e recomendações são estimativas de grade meteorológica e devem ser confirmadas no talhão.</p>
        </div>
    </section>
@empty
    <div class="empty">
        <div class="empty-icon"><i class="ti ti-cloud-off"></i></div>
        <p class="empty-title">Nenhum talhão disponível para análise</p>
        <p class="empty-subtitle text-secondary">Cadastre propriedades com coordenadas e seus talhões para visualizar a inteligência climática.</p>
    </div>
@endforelse

<style>
    .climate-location > .card-header { padding: 1rem 1.25rem; }
    .climate-day { border-color: var(--tblr-border-color); }
    .climate-day > .card-header { background: var(--tblr-bg-surface-secondary); }
    .climate-metrics { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .65rem; }
    .climate-metrics > div { display: flex; flex-direction: column; gap: .1rem; padding: .55rem .65rem; border-radius: .375rem; background: var(--tblr-bg-surface-secondary); min-width: 0; }
    .climate-metrics span { color: var(--tblr-secondary); font-size: .72rem; }
    .climate-metrics strong { font-size: .9rem; overflow-wrap: anywhere; }
    .climate-recommendations { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: .65rem; }
    .climate-recommendation { border-top: 1px solid var(--tblr-border-color); padding-top: .65rem; min-width: 0; }
    @media (max-width: 767px) { .climate-metrics { grid-template-columns: repeat(2, minmax(0, 1fr)); } .climate-recommendations { grid-template-columns: 1fr; } }
</style>
