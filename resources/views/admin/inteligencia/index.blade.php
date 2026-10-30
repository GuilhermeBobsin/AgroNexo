{{-- admin/inteligencia/index.blade.php --}}
@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body">
    <div class="container-xl">

        {{-- Cabeçalho --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center">
                        <span class="avatar bg-blue-lt me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 18a4.6 4.4 0 0 1 0 -9a5 4.5 0 0 1 11 2h1a3.5 3.5 0 0 1 0 7" />
                                <path d="M11 13v2m0 3v2m4 -5v2m0 3v2" />
                            </svg>
                        </span>
                        <div>
                            <h1 class="page-title mb-0">Inteligência climática</h1>
                            <div class="text-secondary">Recomendações automáticas de operação para os próximos sete dias.</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <form method="POST" action="{{ route('admin.inteligencia.sincronizar') }}">
                        @csrf
                        <button class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                            </svg>
                            Atualizar previsão agora
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mensagens de sessão --}}
        @foreach (['success' => 'success', 'error' => 'danger'] as $key => $class)
            @if (session($key))
                <div class="alert alert-{{ $class }} alert-dismissible" role="alert">
                    <div class="alert-icon">
                        @if ($class === 'success')
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon">
                                <path d="M5 12l5 5l10 -10"></path>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon">
                                <path d="M12 9v4" />
                                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                <path d="M12 16h.01" />
                            </svg>
                        @endif
                    </div>
                    <div>{{ session($key) }}</div>
                    <a href="#" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></a>
                </div>
            @endif
        @endforeach

        {{-- Feedback de sincronização em andamento — estrutura e script originais, intocados --}}
        @if (session('clima_sync_solicitada_em'))
            <div id="clima-sync-feedback" class="alert alert-info" role="status"
                data-desde="{{ session('clima_sync_solicitada_em') }}"
                data-status-url="{{ route('admin.inteligencia.sincronizacao.status') }}">
                Aguardando a conclusão da sincronização climática…
            </div>
            <script>
                (() => {
                    const feedback = document.getElementById('clima-sync-feedback');
                    if (!feedback) return;
                    const desde = feedback.dataset.desde;
                    const url = new URL(feedback.dataset.statusUrl, window.location.origin);
                    url.searchParams.set('desde', desde);
                    let tentativas = 0;

                    const consultar = async () => {
                        tentativas++;
                        try {
                            const response = await fetch(url, { headers: { Accept: 'application/json' } });
                            if (!response.ok) throw new Error('Não foi possível consultar o status da sincronização.');
                            const status = await response.json();
                            if (status.atualizada) {
                                feedback.className = 'alert alert-success';
                                feedback.textContent = 'Previsão atualizada. Recarregando os dados…';
                                window.setTimeout(() => window.location.reload(), 800);
                                return;
                            }
                        } catch (error) {
                            feedback.className = 'alert alert-warning';
                            feedback.textContent = error.message;
                            return;
                        }

                        if (tentativas >= 15) {
                            feedback.className = 'alert alert-warning';
                            feedback.textContent = 'A fila ainda não atualizou os dados. Verifique se o worker está ativo (`composer run dev` ou `php artisan queue:listen`) e consulte `php artisan queue:failed`.';
                            return;
                        }
                        window.setTimeout(consultar, 2000);
                    };

                    window.setTimeout(consultar, 1500);
                })();
            </script>
        @endif

        {{-- Regras automáticas — mesmo texto, container mais legível --}}
        <div class="card mb-4">
            <div class="card-body d-flex gap-3">
                <span class="avatar bg-azure-lt flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 9h.01" />
                        <path d="M11 12h1v4h1" />
                    </svg>
                </span>
                <div>
                    <div class="fw-bold mb-1">Como as recomendações são calculadas</div>
                    <p class="text-secondary small mb-0">
                        Pulverização: vento abaixo de 3 km/h gera atenção; 3–6,5 km/h é favorável; acima de 6,5 até 9,5 km/h gera atenção; acima de 9,5 km/h é desfavorável. Umidade relativa abaixo de 55% ou temperatura acima de 30 °C também torna a avaliação desfavorável. Chuva durante ou até duas horas após o horário avaliado gera alerta — respeite o intervalo sem chuva da bula. Epagri recomenda cautela adicional acima de 28 °C para gotas finas. Aração: a umidade estimada do solo (camada de 3–9 cm) é o indicador principal; a faixa 0,12–0,30 m³/m³ é apenas provisória, pois friabilidade varia por solo. Confirme com teste do torrão e avaliação de trafegabilidade. Calagem: abaixo de 10 km/h favorável; 10–15 atenção; acima de 15 até 30 desfavorável; acima de 30 alerta de perda elevada/proibitiva. Os limites de vento da calagem são parâmetros operacionais do AgroNexo, não faixas universais publicadas pelas fontes. Essas orientações alertam, mas não bloqueiam tarefas. O modelo usa uma grade próxima das coordenadas, não mede o talhão; confirme a condição local e siga recomendações técnicas e rótulo/bula. Fontes: <a href="https://publicacoes.epagri.sc.gov.br/SP/article/download/1587/1458" target="_blank" rel="noopener">Epagri — Tecnologia de aplicação</a>, <a href="https://www.infoteca.embrapa.br/infoteca/bitstream/doc/1179274/1/DOC-477.pdf" target="_blank" rel="noopener">Embrapa — Tecnologia de aplicação</a>, <a href="https://www.infoteca.cnptia.embrapa.br/infoteca/bitstream/doc/1040211/1/ID43609-2016LVTrigo.pdf" target="_blank" rel="noopener">Embrapa — avaliação de friabilidade</a> e <a href="https://www.infoteca.cnptia.embrapa.br/infoteca/bitstream/doc/1015113/1/p59.pdf" target="_blank" rel="noopener">Embrapa — manejo do calcário</a>. Atualização automática de hora em hora depende do agendador Laravel.
                    </p>
                    <div class="text-secondary small mt-2">
                        Última atualização geral: <strong>{{ $atualizadoEm ? \Illuminate\Support\Carbon::parse($atualizadoEm)->format('d/m/Y H:i') : 'ainda não sincronizada' }}</strong>
                    </div>
                </div>
            </div>
        </div>

        @include('components.painel-inteligencia-clima')
    </div>
</main>
@endsection
