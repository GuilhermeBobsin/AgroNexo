@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body">
    <div class="container-xl">

        {{-- Alertas de Sessão / Erros --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <span class="avatar avatar-xs bg-success-lt me-2 rounded-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M5 12l5 5l10 -10"/></svg>
                    </span>
                    <div class="fw-semibold">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        {{-- Hero Header do Agrônomo --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center justify-content-between g-3">
                <div class="col-12 col-md">
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge bg-purple-lt text-uppercase fw-bold letter-spacing-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 3c-1.2 0 -2.4 .6 -3 1.7c-2.4 -.3 -4.5 1.5 -4.8 3.9c-1.5 .8 -2.2 2.6 -1.8 4.3c.5 2 2.2 3.4 4.3 3.4c.5 2.1 2.2 3.7 4.3 3.7s3.8 -1.6 4.3 -3.7c2.1 0 3.8 -1.4 4.3 -3.4c.4 -1.7 -.3 -3.5 -1.8 -4.3c-.3 -2.4 -2.4 -4.2 -4.8 -3.9c-.6 -1.1 -1.8 -1.7 -3 -1.7z"/><path d="M12 12l0 9"/></svg>
                            Manejo & Consultoria Agronômica
                        </span>
                        <span class="text-secondary small d-none d-sm-inline">·</span>
                        <span class="text-secondary small d-none d-sm-inline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M4 11h16"/></svg>
                            {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
                        </span>
                    </div>
                    <h1 class="page-title fs-2 mb-1">
                        Olá, {{ $usuario->name }}
                    </h1>
                    <p class="text-secondary mb-0">
                        Acompanhe as atividades e aplicações das propriedades vinculadas a você.
                    </p>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('agronomo.inteligencia.index') }}" class="btn btn-outline-secondary shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M20 16.2a4.5 4.5 0 0 0 -2.5 -8.5a6 6 0 0 0 -11.5 1.8a3.5 3.5 0 0 0 .5 6.7h13.5"></path><path d="M8 19l-1 2M13 19l-1 2M18 19l-1 2"></path></svg>
                            Inteligência Climática
                        </a>
                        <a href="{{ route('agronomo.recomendacoes.create') }}" class="btn btn-primary shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                            Nova recomendação técnica
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Avisos Operacionais (Atrasos e Recomendações) --}}
        @if ($indicadores['tarefas_atrasadas'] > 0)
            <div class="alert alert-danger shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <span class="avatar avatar-sm bg-danger text-danger-fg me-3 rounded-circle shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 9v4"/><path d="M12 16h.01"/></svg>
                        </span>
                        <div>
                            <div class="fw-bold mb-1">Atenção: Tarefas de campo com prazo vencido</div>
                            <div class="text-secondary small">
                                Existem <strong>{{ $indicadores['tarefas_atrasadas'] }}</strong> tarefa(s) com data prevista anterior a hoje nas propriedades que você assessora.
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('agronomo.tarefas.index') }}" class="btn btn-danger btn-sm shadow-sm">
                        Ver tarefas
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M5 12l14 0"/><path d="M13 18l6 -6"/><path d="M13 6l6 6"/></svg>
                    </a>
                </div>
            </div>
        @endif

        @if ($indicadores['recomendacoes_pendentes'] > 0)
            <div class="alert alert-warning shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <span class="avatar avatar-sm bg-warning text-warning-fg me-3 rounded-circle shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M12 9v4" />
                                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                <path d="M12 16h.01" />
                            </svg>
                        </span>
                        <div>
                            <div class="fw-bold mb-1">Recomendações técnicas em análise</div>
                            <div class="text-secondary small">
                                Você possui <strong>{{ $indicadores['recomendacoes_pendentes'] }}</strong> recomendação(ões) aguardando avaliação e aprovação do produtor para virar tarefa de campo.
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('agronomo.recomendacoes.index', ['status' => 'pendente']) }}" class="btn btn-warning btn-sm shadow-sm">
                        Acompanhar pendências
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M5 12l14 0"/><path d="M13 18l6 -6"/><path d="M13 6l6 6"/></svg>
                    </a>
                </div>
            </div>
        @endif

        @if ($propriedades->isEmpty())
            <div class="card shadow-sm border-0 py-5 text-center">
                <div class="empty">
                    <div class="empty-icon text-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M3 21l18 0"/><path d="M5 21v-14l8 -4l6 3v15"/><path d="M9 21v-8h4v8"/></svg>
                    </div>
                    <p class="empty-title mb-1">Nenhuma propriedade vinculada</p>
                    <p class="empty-subtitle text-secondary mb-3">
                        Peça ao administrador para associar as fazendas e talhões que você presta consultoria técnica.
                    </p>
                </div>
            </div>
        @else
            {{-- Indicadores / KPIs Modernos --}}
            <div class="row row-deck row-cards mb-4">

                {{-- Propriedades & Área --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-hover-effect shadow-sm border-start border-3 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="text-secondary small fw-semibold text-uppercase">Fazendas Acompanhadas</div>
                                <span class="avatar avatar-sm bg-primary-lt rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M3 21l18 0"/><path d="M5 21v-14l8 -4l6 3v15"/><path d="M9 21v-8h4v8"/></svg>
                                </span>
                            </div>
                            <div class="h1 fw-bold mb-1">{{ $indicadores['propriedades'] }}</div>
                            <div class="text-secondary small">
                                <strong>{{ $indicadores['talhoes'] }}</strong> talhões · <strong>{{ number_format((float) $indicadores['area'], 2, ',', '.') }} ha</strong>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tarefas em Aberto --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-hover-effect shadow-sm border-start border-3 border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="text-secondary small fw-semibold text-uppercase">Tarefas em Aberto</div>
                                <span class="avatar avatar-sm bg-azure-lt rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M9 11l3 3l8 -8"/><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/></svg>
                                </span>
                            </div>
                            <div class="h1 fw-bold mb-1 text-azure">
                                {{ $indicadores['tarefas_pendentes'] + $indicadores['tarefas_em_andamento'] }}
                            </div>
                            <div class="text-secondary small">
                                <span class="badge bg-yellow-lt me-1">{{ $indicadores['tarefas_pendentes'] }} pendentes</span>
                                <span class="badge bg-azure-lt">{{ $indicadores['tarefas_em_andamento'] }} em andamento</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aplicações no Mês --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-hover-effect shadow-sm border-start border-3 border-purple">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="text-secondary small fw-semibold text-uppercase">Aplicações no Mês</div>
                                <span class="avatar avatar-sm bg-purple-lt rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                    </svg>
                                </span>
                            </div>
                            <div class="h1 fw-bold mb-1 text-purple">{{ $indicadores['aplicacoes_mes'] }}</div>
                            <div class="text-secondary small">
                                Insumos executados no mês atual
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Recomendações Pendentes --}}
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-hover-effect shadow-sm border-start border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <div class="text-secondary small fw-semibold text-uppercase">Recomendações Pendentes</div>
                                <span class="avatar avatar-sm bg-yellow-lt rounded-circle">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                                        <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                                        <path d="M9 14l2 2l4 -4"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="h1 fw-bold mb-1 text-warning">{{ $indicadores['recomendacoes_pendentes'] }}</div>
                            <div class="text-secondary small">
                                Aguardando decisão do produtor
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Grade Operacional: Tarefas em Aberto (7) + Aplicações Recentes (5) --}}
            <div class="row row-cards mb-4">

                {{-- Tarefas em Aberto --}}
                <div class="col-12 col-xl-7">
                    <section class="card shadow-sm h-100" aria-labelledby="tarefas-title">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm bg-primary-lt rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M9 11l3 3l8 -8"/><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/></svg>
                                </span>
                                <div>
                                    <h2 class="card-title mb-0" id="tarefas-title">Tarefas em Aberto</h2>
                                    <div class="text-secondary small">Atividades de campo em andamento e pendentes</div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <a href="{{ route('agronomo.tarefas.index') }}" class="btn btn-outline-primary btn-sm">
                                    Ver todas
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M5 12l14 0"/><path d="M13 18l6 -6"/><path d="M13 6l6 6"/></svg>
                                </a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-vcenter table-hover card-table">
                                <thead>
                                    <tr>
                                        <th>Tarefa</th>
                                        <th>Propriedade / Talhão</th>
                                        <th>Responsável</th>
                                        <th>Previsão</th>
                                        <th>Status</th>
                                        <th class="w-1"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($proximasTarefas as $tarefa)
                                        @php
                                            $corStatus = match ($tarefa->status) {
                                                'em_andamento' => 'azure',
                                                'concluida' => 'green',
                                                'cancelada' => 'red',
                                                default => 'yellow',
                                            };
                                            $rotuloStatus = match ($tarefa->status) {
                                                'em_andamento' => 'Em andamento',
                                                'concluida' => 'Concluída',
                                                'cancelada' => 'Cancelada',
                                                default => 'Pendente',
                                            };
                                            $previsao = $tarefa->data_prevista;
                                            $atrasada = $previsao && $previsao->isPast() && !$previsao->isToday();
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="status-dot status-{{ $corStatus }} me-2"></span>
                                                    <div>
                                                        <a class="fw-bold text-reset text-decoration-none" href="{{ route('agronomo.tarefas.show', $tarefa) }}">
                                                            {{ $tarefa->titulo }}
                                                        </a>
                                                        <div class="mt-1">
                                                            <span class="badge bg-secondary-lt text-capitalize py-0 px-1">{{ ucfirst(str_replace('_', ' ', $tarefa->tipo)) }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="fw-semibold">{{ $tarefa->propriedade->nome }}</div>
                                                <div class="text-secondary small">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5"/><path d="M9 4v13"/></svg>
                                                    {{ $tarefa->talhao->nome ?? 'Sem talhão' }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="avatar avatar-xs bg-secondary-lt rounded-circle me-2 fw-semibold">
                                                        {{ strtoupper(substr($tarefa->responsavel?->name ?? '?', 0, 1)) }}
                                                    </span>
                                                    <span class="small">{{ $tarefa->responsavel?->name ?? 'Não atribuído' }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                @if ($previsao)
                                                    <span class="small {{ $atrasada ? 'text-danger fw-bold' : ($previsao->isToday() ? 'text-warning fw-bold' : 'text-secondary') }}">
                                                        {{ $previsao->format('d/m/Y') }}
                                                        @if ($atrasada)
                                                            <span class="badge bg-danger-lt ms-1">Atrasada</span>
                                                        @elseif ($previsao->isToday())
                                                            <span class="badge bg-warning-lt ms-1">Hoje</span>
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="text-secondary small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $corStatus }}-lt">{{ $rotuloStatus }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('agronomo.tarefas.show', $tarefa) }}" class="btn btn-ghost-secondary btn-icon btn-sm" title="Ver detalhes">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M9 6l6 6l-6 6"/></svg>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-secondary py-5">
                                                <div class="empty">
                                                    <div class="empty-icon text-secondary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><path d="M9 14l2 2l4 -4"/></svg>
                                                    </div>
                                                    <p class="empty-title mb-1">Não há tarefas pendentes ou em andamento</p>
                                                    <p class="empty-subtitle text-secondary mb-0">Todas as operações estão em dia nas propriedades atendidas.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                {{-- Aplicações Recentes --}}
                <div class="col-12 col-xl-5">
                    <section class="card shadow-sm h-100" aria-labelledby="aplicacoes-title">
                        <div class="card-header py-3 d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center gap-2">
                                <span class="avatar avatar-sm bg-purple-lt rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                    </svg>
                                </span>
                                <div>
                                    <h2 class="card-title mb-0" id="aplicacoes-title">Aplicações Recentes</h2>
                                    <div class="text-secondary small">Últimos registros de pulverização no campo</div>
                                </div>
                            </div>
                            <div class="card-actions">
                                <a href="{{ route('agronomo.aplicacoes.index') }}" class="btn btn-outline-secondary btn-sm">
                                    Ver todas
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M5 12l14 0"/><path d="M13 18l6 -6"/><path d="M13 6l6 6"/></svg>
                                </a>
                            </div>
                        </div>

                        <div class="list-group list-group-flush list-group-hoverable">
                            @forelse ($aplicacoesRecentes as $aplicacao)
                                <a href="{{ route('agronomo.aplicacoes.show', $aplicacao) }}" class="list-group-item list-group-item-action py-3">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="avatar avatar-sm bg-purple-lt rounded-circle">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M6 18a6 6 0 0 1 12 0c0 3 -2 5 -6 5s-6 -2 -6 -5z"/><path d="M12 3v5"/></svg>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="fw-bold text-reset">{{ $aplicacao->produto?->nome ?? 'Produto' }}</span>
                                                <span class="text-secondary small">{{ $aplicacao->data_aplicacao?->format('d/m/Y') }}</span>
                                            </div>
                                            <div class="d-flex flex-wrap align-items-center gap-1 text-secondary small">
                                                <span class="badge bg-secondary-lt">{{ $aplicacao->talhao?->propriedade?->nome ?? '—' }}</span>
                                                <span class="badge bg-secondary-lt">{{ $aplicacao->talhao?->nome ?? 'Talhão' }}</span>
                                                @if ($aplicacao->dose)
                                                    <span class="badge bg-purple-lt">{{ number_format((float) $aplicacao->dose, 2, ',', '.') }} {{ $aplicacao->produto?->unidade ?? 'dose' }}</span>
                                                @endif
                                                <span class="text-secondary ms-auto">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                                                    {{ $aplicacao->usuario?->name ?? 'Operador' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="card-body py-5 text-center">
                                    <div class="empty">
                                        <div class="empty-icon text-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" /></svg>
                                        </div>
                                        <p class="empty-title mb-1">Nenhuma aplicação registrada</p>
                                        <p class="empty-subtitle text-secondary mb-0">Ainda não há aplicações registradas nas suas propriedades.</p>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </section>
                </div>

            </div>

            {{-- Recomendações Aguardando Análise do Produtor --}}
            <section class="card shadow-sm mb-4" aria-labelledby="recomendacoes-pendentes-title">
                <div class="card-header py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar avatar-sm bg-yellow-lt rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                                <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                                <path d="M9 14l2 2l4 -4"></path>
                            </svg>
                        </span>
                        <div>
                            <h2 class="card-title mb-0" id="recomendacoes-pendentes-title">Recomendações Técnicas Aguardando Análise</h2>
                            <div class="text-secondary small">Prescrições agronômicas submetidas para aprovação do produtor</div>
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('agronomo.recomendacoes.index', ['status' => 'pendente']) }}" class="btn btn-outline-secondary btn-sm">
                            Ver todas
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M5 12l14 0"/><path d="M13 18l6 -6"/><path d="M13 6l6 6"/></svg>
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-vcenter table-hover card-table">
                        <thead>
                            <tr>
                                <th>Recomendação</th>
                                <th>Propriedade / Talhão</th>
                                <th>Prioridade</th>
                                <th>Data de Envio</th>
                                <th class="text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recomendacoesPendentes as $recomendacao)
                                @php
                                    $corPrioridade = match ($recomendacao->prioridade) {
                                        'alta', 'urgente' => 'red',
                                        'media' => 'yellow',
                                        default => 'azure',
                                    };
                                @endphp
                                <tr>
                                    <td>
                                        <a href="{{ route('agronomo.recomendacoes.show', $recomendacao) }}" class="fw-bold text-reset text-decoration-none">
                                            {{ $recomendacao->titulo }}
                                        </a>
                                        <div class="small text-secondary mt-1">
                                            <span class="badge bg-secondary-lt text-capitalize py-0 px-1">{{ ucfirst(str_replace('_', ' ', $recomendacao->tipo)) }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $recomendacao->propriedade->nome }}</div>
                                        <div class="small text-secondary">
                                            {{ $recomendacao->talhao?->nome ?? 'Toda a propriedade' }}
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $corPrioridade }}-lt">{{ ucfirst($recomendacao->prioridade) }}</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary small">{{ $recomendacao->created_at->format('d/m/Y') }}</span>
                                    </td>
                                    <td class="text-end">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ route('agronomo.recomendacoes.show', $recomendacao) }}">
                                            Acompanhar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-5">
                                        <div class="empty">
                                            <p class="empty-title mb-1">Nenhuma recomendação aguardando análise</p>
                                            <p class="empty-subtitle text-secondary mb-0">Todas as recomendações enviadas já foram analisadas pelo produtor.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Propriedades Acompanhadas --}}
            <section class="mb-4" aria-labelledby="propriedades-title">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <span class="avatar avatar-sm bg-green-lt rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M3 21l18 0"/><path d="M5 21v-14l8 -4l6 3v15"/><path d="M9 21v-8h4v8"/></svg>
                        </span>
                        <div>
                            <h2 class="card-title mb-0" id="propriedades-title">Propriedades Acompanhadas</h2>
                            <div class="text-secondary small">Fazendas cadastradas vinculadas ao seu usuário técnico</div>
                        </div>
                    </div>
                </div>

                <div class="row row-cards">
                    @foreach ($propriedades as $propriedade)
                        <div class="col-12 col-md-6 col-xl-4">
                            <div class="card card-hover-effect shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-start justify-content-between mb-2">
                                        <h3 class="card-title mb-0">
                                            {{ $propriedade->nome }}
                                        </h3>
                                        <span class="badge bg-green-lt">Ativa</span>
                                    </div>
                                    <div class="text-secondary small mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5"/><path d="M9 4v13"/></svg>
                                        {{ $propriedade->localizacao ?? 'Localização não cadastrada' }}
                                    </div>

                                    <div class="row g-2 pt-2 border-top">
                                        <div class="col-6">
                                            <div class="text-secondary small">Talhões</div>
                                            <div class="h3 mb-0">{{ $propriedade->talhoes_count }}</div>
                                        </div>
                                        <div class="col-6">
                                            <div class="text-secondary small">Tarefas</div>
                                            <div class="h3 mb-0">{{ $propriedade->tarefas_count }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

    </div>
</main>
@endsection
