@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body">
    <div class="container-xl">

        {{-- Cabeçalho --}}
        <div class="page-header d-print-none mb-4">
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
                    <div class="text-secondary">Recomendações automáticas por talhão para os próximos sete dias.</div>
                </div>
            </div>
        </div>

        {{-- Critérios orientativos --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="avatar avatar-sm bg-azure-lt me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                        <path d="M12 9h.01" />
                        <path d="M11 12h1v4h1" />
                    </svg>
                </span>

                <h3 class="card-title">Critérios orientativos</h3>
                <div class="col-auto float-end ms-auto">
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

            <div class="card-body">
                <div class="row g-3 mb-3">

                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <span class="avatar avatar-sm bg-blue-lt flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                </svg>
                            </span>
                            <div>
                                <div class="fw-bold mb-1">Pulverização</div>
                                <p class="text-secondary small mb-0">
                                    Vento abaixo de 3 km/h gera atenção; 3–6,5 km/h é favorável; acima de 6,5 até 9,5 km/h gera atenção; acima de 9,5 km/h é desfavorável. Umidade relativa abaixo de 55% ou temperatura acima de 30 °C também torna a avaliação desfavorável. Chuva durante ou até duas horas após o horário avaliado gera alerta; respeite o intervalo sem chuva da bula. A Epagri recomenda cautela adicional acima de 28 °C para gotas finas.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <span class="avatar avatar-sm bg-orange-lt flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M4 20v-10a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v10" />
                                    <path d="M4 20h16" />
                                    <path d="M8 4v4" />
                                    <path d="M16 4v4" />
                                </svg>
                            </span>
                            <div>
                                <div class="fw-bold mb-1">Aração</div>
                                <p class="text-secondary small mb-0">
                                    A umidade estimada do solo (3–9 cm) é o indicador principal. A faixa 0,12–0,30 m³/m³ é provisória e deve ser confirmada com teste do torrão, pois a friabilidade varia por solo.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <span class="avatar avatar-sm bg-green-lt flex-shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 4l-8 4l8 4l8 -4l-8 -4" />
                                    <path d="M4 12l8 4l8 -4" />
                                    <path d="M4 16l8 4l8 -4" />
                                </svg>
                            </span>
                            <div>
                                <div class="fw-bold mb-1">Calagem</div>
                                <p class="text-secondary small mb-0">
                                    Abaixo de 10 km/h favorável; 10–15 atenção; acima de 15 até 30 desfavorável; acima de 30 alerta de perda elevada/proibitiva. Os limites de vento da calagem são parâmetros operacionais do AgroNexo, não faixas universais das fontes.
                                </p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="text-secondary small border-top pt-3">
                    As orientações alertam, mas não bloqueiam tarefas. O modelo usa grade próxima às coordenadas, não mede o talhão; confirme a condição local e siga recomendações técnicas e rótulo/bula.
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <a href="https://publicacoes.epagri.sc.gov.br/SP/article/download/1587/1458" target="_blank" rel="noopener" class="badge bg-secondary-lt text-decoration-none">
                        Epagri — Tecnologia de aplicação
                    </a>
                    <a href="https://www.infoteca.embrapa.br/infoteca/bitstream/doc/1179274/1/DOC-477.pdf" target="_blank" rel="noopener" class="badge bg-secondary-lt text-decoration-none">
                        Embrapa — Tecnologia de aplicação
                    </a>
                    <a href="https://www.infoteca.cnptia.embrapa.br/infoteca/bitstream/doc/1040211/1/ID43609-2016LVTrigo.pdf" target="_blank" rel="noopener" class="badge bg-secondary-lt text-decoration-none">
                        Embrapa — avaliação de friabilidade
                    </a>
                    <a href="https://www.infoteca.cnptia.embrapa.br/infoteca/bitstream/doc/1015113/1/p59.pdf" target="_blank" rel="noopener" class="badge bg-secondary-lt text-decoration-none">
                        Embrapa — manejo do calcário
                    </a>
                </div>

                <div class="text-secondary small mt-3">
                    Última atualização geral: <strong>{{ $atualizadoEm ? \Illuminate\Support\Carbon::parse($atualizadoEm)->format('d/m/Y H:i') : 'ainda não sincronizada' }}</strong>
                </div>
            </div>
        </div>

        @include('components.painel-inteligencia-clima')
    </div>
</main>
@endsection