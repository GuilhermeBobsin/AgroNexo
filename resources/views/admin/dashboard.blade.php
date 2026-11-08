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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <span class="avatar avatar-xs bg-danger-lt me-2 rounded-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 9v4"/><path d="M12 16h.01"/></svg>
                    </span>
                    <div>{{ $errors->first() }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        @endif

        {{-- Banner de Recomendações Pendentes (quando houver) --}}
        @if ($recomendacoesPendentes > 0)
            <div class="alert alert-warning alert-dismissible shadow-sm mb-4" role="alert">
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
                            <div class="fw-bold mb-1">Recomendações Técnicas Pendentes</div>
                            <div class="text-secondary small">
                                Existem <strong>{{ $recomendacoesPendentes }}</strong> alerta(s) agronômico(s) ou recomendação(ões) ativa(s) aguardando sua revisão e decisão.
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.recomendacoes.index', ['status' => 'pendente']) }}" class="btn btn-warning btn-sm shadow-sm">
                        Revisar recomendações
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M5 12l14 0"/><path d="M13 18l6 -6"/><path d="M13 6l6 6"/></svg>
                    </a>
                </div>
            </div>
        @endif

        {{-- Hero Header Moderno --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center justify-content-between g-3">
                <div class="col-12 col-md">
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge bg-green-lt text-uppercase fw-bold letter-spacing-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 3c-1.2 0 -2.4 .6 -3 1.7c-2.4 -.3 -4.5 1.5 -4.8 3.9c-1.5 .8 -2.2 2.6 -1.8 4.3c.5 2 2.2 3.4 4.3 3.4c.5 2.1 2.2 3.7 4.3 3.7s3.8 -1.6 4.3 -3.7c2.1 0 3.8 -1.4 4.3 -3.4c.4 -1.7 -.3 -3.5 -1.8 -4.3c-.3 -2.4 -2.4 -4.2 -4.8 -3.9c-.6 -1.1 -1.8 -1.7 -3 -1.7z"/><path d="M12 12l0 9"/></svg>
                            Gestão Agrícola Integrada
                        </span>
                        <span class="text-secondary small d-none d-sm-inline">·</span>
                        <span class="text-secondary small d-none d-sm-inline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M4 11h16"/></svg>
                            {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
                        </span>
                    </div>
                    <h1 class="page-title fs-2 mb-1">
                        Dashboard do Produtor
                    </h1>
                    <p class="text-secondary mb-0">
                        Olá, <strong>{{ auth()->user()->name }}</strong>. Acompanhe a operação, o status das tarefas e o manejo de suas propriedades rurais.
                    </p>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.propriedades.create') }}" class="btn btn-outline-secondary shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M3 21l18 0"/><path d="M5 21v-14l8 -4l6 3v15"/><path d="M9 21v-8h4v8"/></svg>
                            Nova propriedade
                        </a>
                        <a href="{{ route('admin.tarefas.create') }}" class="btn btn-primary shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                            Nova tarefa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros Operacionais --}}
        @php
            $temFiltroAtivo = request()->filled('propriedade_id') || request()->filled('responsavel_id') || request()->filled('data_inicio') || request()->filled('data_fim');
        @endphp
        <div class="card mb-4 shadow-sm">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-xs bg-primary-lt rounded-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M4 6h16"/><path d="M7 12h10"/><path d="M10 18h4"/></svg>
                    </span>
                    <span class="fw-semibold text-secondary small text-uppercase">Filtrar dados da operação</span>
                    @if ($temFiltroAtivo)
                        <span class="badge bg-primary text-primary-fg ms-1">Filtros ativos</span>
                    @endif
                </div>
                @if ($temFiltroAtivo)
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-link btn-sm text-decoration-none text-secondary p-0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M18 6l-12 12"/><path d="M6 6l12 12"/></svg>
                        Limpar todos
                    </a>
                @endif
            </div>
            <form method="GET" action="{{ route('admin.dashboard') }}" class="card-body pt-3 pb-3">
                <div class="row g-2 align-items-end">
                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label small text-secondary mb-1" for="propriedade_id">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1 text-secondary"><path d="M3 21l18 0"/><path d="M5 21v-14l8 -4l6 3v15"/><path d="M9 21v-8h4v8"/></svg>
                            Propriedade
                        </label>
                        <select class="form-select form-select-sm" name="propriedade_id" id="propriedade_id">
                            <option value="">Todas as propriedades</option>
                            @foreach ($propriedades as $propriedade)
                                <option value="{{ $propriedade->id }}" @selected(request('propriedade_id') == $propriedade->id)>
                                    {{ $propriedade->nome }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-md-6 col-lg-3">
                        <label class="form-label small text-secondary mb-1" for="responsavel_id">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1 text-secondary"><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                            Operador responsável
                        </label>
                        <select class="form-select form-select-sm" name="responsavel_id" id="responsavel_id">
                            <option value="">Todos os operadores</option>
                            @foreach ($operadores as $operador)
                                <option value="{{ $operador->id }}" @selected(request('responsavel_id') == $operador->id)>
                                    {{ $operador->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-lg-2">
                        <label class="form-label small text-secondary mb-1" for="data_inicio">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1 text-secondary"><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M4 11h16"/></svg>
                            De
                        </label>
                        <input class="form-control form-control-sm" type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}">
                    </div>

                    <div class="col-6 col-lg-2">
                        <label class="form-label small text-secondary mb-1" for="data_fim">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1 text-secondary"><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M4 11h16"/></svg>
                            Até
                        </label>
                        <input class="form-control form-control-sm" type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}">
                    </div>

                    <div class="col-12 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M4 6h16"/><path d="M7 12h10"/><path d="M10 18h4"/></svg>
                            Filtrar
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm" title="Limpar filtros">
                            Limpar
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Indicadores / KPIs Modernos --}}
        @php
            $totalTarefas = $indicadores['tarefas'];
            $pctPendentes = $totalTarefas > 0 ? round(($indicadores['pendentes'] / $totalTarefas) * 100) : 0;
            $pctEmAndamento = $totalTarefas > 0 ? round(($indicadores['em_andamento'] / $totalTarefas) * 100) : 0;
            $pctConcluidas = $totalTarefas > 0 ? round(($indicadores['concluidas'] / $totalTarefas) * 100) : 0;
        @endphp
        <div class="row row-deck row-cards mb-4">

            {{-- Total de Tarefas --}}
            <div class="col-sm-6 col-xl">
                <div class="card card-hover-effect shadow-sm border-start border-3 border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-secondary small fw-semibold text-uppercase">Tarefas no Período</div>
                            <span class="avatar avatar-sm bg-primary-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
                                    <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"/>
                                    <path d="M9 14l2 2l4 -4"/>
                                </svg>
                            </span>
                        </div>
                        <div class="h1 fw-bold mb-1">{{ number_format($indicadores['tarefas'], 0, ',', '.') }}</div>
                        <div class="text-secondary small">
                            Total programado
                        </div>
                    </div>
                </div>
            </div>

            {{-- Pendentes --}}
            <div class="col-sm-6 col-xl">
                <div class="card card-hover-effect shadow-sm border-start border-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-secondary small fw-semibold text-uppercase">Pendentes</div>
                            <span class="avatar avatar-sm bg-yellow-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M12 7v5l3 3" />
                                </svg>
                            </span>
                        </div>
                        <div class="h1 fw-bold mb-1 text-warning">{{ number_format($indicadores['pendentes'], 0, ',', '.') }}</div>
                        <div class="d-flex align-items-center text-secondary small">
                            <span class="badge bg-yellow-lt me-1">{{ $pctPendentes }}%</span> do total
                        </div>
                    </div>
                </div>
            </div>

            {{-- Em Andamento --}}
            <div class="col-sm-6 col-xl">
                <div class="card card-hover-effect shadow-sm border-start border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-secondary small fw-semibold text-uppercase">Em Andamento</div>
                            <span class="avatar avatar-sm bg-azure-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                </svg>
                            </span>
                        </div>
                        <div class="h1 fw-bold mb-1 text-azure">{{ number_format($indicadores['em_andamento'], 0, ',', '.') }}</div>
                        <div class="d-flex align-items-center text-secondary small">
                            <span class="badge bg-azure-lt me-1">{{ $pctEmAndamento }}%</span> em execução
                        </div>
                    </div>
                </div>
            </div>

            {{-- Concluídas --}}
            <div class="col-sm-6 col-xl">
                <div class="card card-hover-effect shadow-sm border-start border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-secondary small fw-semibold text-uppercase">Concluídas</div>
                            <span class="avatar avatar-sm bg-green-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M9 12l2 2l4 -4" />
                                </svg>
                            </span>
                        </div>
                        <div class="h1 fw-bold mb-1 text-green">{{ number_format($indicadores['concluidas'], 0, ',', '.') }}</div>
                        <div class="d-flex align-items-center text-secondary small">
                            <span class="badge bg-green-lt me-1">{{ $pctConcluidas }}%</span> taxa de êxito
                        </div>
                    </div>
                </div>
            </div>

            {{-- Aplicações Realizadas --}}
            <div class="col-sm-6 col-xl">
                <div class="card card-hover-effect shadow-sm border-start border-3 border-purple">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-secondary small fw-semibold text-uppercase">Aplicações Feitas</div>
                            <span class="avatar avatar-sm bg-purple-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                </svg>
                            </span>
                        </div>
                        <div class="h1 fw-bold mb-1 text-purple">{{ number_format($indicadores['aplicacoes'], 0, ',', '.') }}</div>
                        <div class="text-secondary small">
                            Defensivos e insumos
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Grade Operacional: Próximas Tarefas (7) + Aplicações Recentes (5) --}}
        <div class="row row-cards mb-4">

            {{-- Coluna 1: Próximas Tarefas --}}
            <div class="col-12 col-xl-7">
                <div class="card shadow-sm h-100">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm bg-primary-lt rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
                                    <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"/>
                                    <path d="M9 12l2 2l4 -4"/>
                                </svg>
                            </span>
                            <div>
                                <h3 class="card-title mb-0">Próximas Tarefas</h3>
                                <div class="text-secondary small">Fila operacional de pendências e execuções</div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('admin.tarefas.index') }}" class="btn btn-outline-primary btn-sm">
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
                                @forelse ($tarefas as $tarefa)
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
                                                    <a class="fw-bold text-reset text-decoration-none" href="{{ route('admin.tarefas.show', $tarefa) }}">
                                                        {{ $tarefa->titulo }}
                                                    </a>
                                                    @if ($tarefa->tipo)
                                                        <div class="mt-1">
                                                            <span class="badge bg-secondary-lt text-capitalize py-0 px-1">{{ $tarefa->tipo }}</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold">{{ $tarefa->propriedade->nome }}</div>
                                            <div class="text-secondary small">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5"/><path d="M9 4v13"/></svg>
                                                {{ $tarefa->talhao?->nome ?? 'Área geral' }}
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
                                            <a href="{{ route('admin.tarefas.show', $tarefa) }}" class="btn btn-ghost-secondary btn-icon btn-sm" title="Ver detalhes">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M9 6l6 6l-6 6"/></svg>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-5 text-center">
                                            <div class="empty">
                                                <div class="empty-icon text-secondary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><path d="M9 14l2 2l4 -4"/></svg>
                                                </div>
                                                <p class="empty-title mb-1">Nenhuma tarefa aberta no momento</p>
                                                <p class="empty-subtitle text-secondary mb-3">Tudo em dia para os filtros aplicados!</p>
                                                <div class="empty-action">
                                                    <a href="{{ route('admin.tarefas.create') }}" class="btn btn-primary btn-sm shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                                        Criar nova tarefa
                                                    </a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Coluna 2: Aplicações Recentes --}}
            <div class="col-12 col-xl-5">
                <div class="card shadow-sm h-100">
                    <div class="card-header py-3 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm bg-purple-lt rounded">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="card-title mb-0">Aplicações Recentes</h3>
                                <div class="text-secondary small">Manejo químico e biológico no campo</div>
                            </div>
                        </div>
                        <div class="card-actions">
                            <a href="{{ route('admin.aplicacoes.index') }}" class="btn btn-outline-secondary btn-sm">
                                Histórico
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M5 12l14 0"/><path d="M13 18l6 -6"/><path d="M13 6l6 6"/></svg>
                            </a>
                        </div>
                    </div>

                    <div class="list-group list-group-flush list-group-hoverable">
                        @forelse ($aplicacoes as $aplicacao)
                            <a href="{{ route('admin.aplicacoes.show', $aplicacao) }}" class="list-group-item list-group-item-action py-3">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="avatar avatar-sm bg-purple-lt rounded-circle">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M6 18a6 6 0 0 1 12 0c0 3 -2 5 -6 5s-6 -2 -6 -5z"/><path d="M12 3v5"/></svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <span class="fw-bold text-reset">{{ $aplicacao->produto?->nome ?? 'Produto não identificado' }}</span>
                                            <span class="text-secondary small">
                                                {{ $aplicacao->data_aplicacao?->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center gap-1 text-secondary small">
                                            <span class="badge bg-secondary-lt">{{ $aplicacao->talhao?->propriedade?->nome ?? '—' }}</span>
                                            <span class="badge bg-secondary-lt">{{ $aplicacao->talhao?->nome ?? 'Talhão geral' }}</span>
                                            @if ($aplicacao->dose)
                                                <span class="badge bg-purple-lt">{{ $aplicacao->dose }} {{ $aplicacao->produto?->unidade_medida ?? 'dose' }}</span>
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
                                    <p class="empty-subtitle text-secondary mb-0">Nenhum registro encontrado para o período ou propriedade.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- Resumo por Propriedade --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm bg-green-lt rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            <path d="M3 21l18 0"></path>
                            <path d="M5 21v-14l8 -4l6 3v15"></path>
                            <path d="M9 21v-8h4v8"></path>
                        </svg>
                    </span>
                    <div>
                        <h2 class="card-title mb-0">Resumo por propriedade</h2>
                        <div class="text-secondary small">Visão consolidada de tarefas, execução e aplicações por unidade agrícola</div>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="{{ route('admin.propriedades.index') }}" class="btn btn-outline-secondary btn-sm">
                        Gerenciar propriedades
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M5 12l14 0"/><path d="M13 18l6 -6"/><path d="M13 6l6 6"/></svg>
                    </a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter table-hover card-table">
                    <thead>
                        <tr>
                            <th>Propriedade</th>
                            <th style="min-width: 140px;">Progresso de Tarefas</th>
                            <th class="text-center">Tarefas</th>
                            <th class="text-center">Pendentes</th>
                            <th class="text-center">Em Andamento</th>
                            <th class="text-center">Concluídas</th>
                            <th class="text-center">Aplicações</th>
                            <th class="text-end">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($resumoPropriedades as $propriedade)
                            @php
                                $totalPropTarefas = $propriedade->resumo['tarefas'];
                                $concluidasProp = $propriedade->resumo['concluidas'];
                                $taxaConclusaoProp = $totalPropTarefas > 0 ? round(($concluidasProp / $totalPropTarefas) * 100) : 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm bg-green-lt rounded-circle me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M3 21l18 0"/><path d="M5 21v-14l8 -4l6 3v15"/><path d="M9 21v-8h4v8"/></svg>
                                        </span>
                                        <div>
                                            <a href="{{ route('admin.propriedades.show', $propriedade) }}" class="fw-bold text-reset text-decoration-none">
                                                {{ $propriedade->nome }}
                                            </a>
                                            <div class="text-secondary small">
                                                {{ $propriedade->localizacao ?? 'Localização não cadastrada' }}
                                                @if (isset($propriedade->talhoes_count))
                                                    · {{ $propriedade->talhoes_count }} talhão(ões)
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress progress-sm flex-fill">
                                            <div class="progress-bar bg-success" style="width: {{ $taxaConclusaoProp }}%" role="progressbar" aria-valuenow="{{ $taxaConclusaoProp }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <span class="small fw-semibold text-secondary">{{ $taxaConclusaoProp }}%</span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold">{{ $propriedade->resumo['tarefas'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-yellow-lt">{{ $propriedade->resumo['pendentes'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-azure-lt">{{ $propriedade->resumo['em_andamento'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-green-lt">{{ $propriedade->resumo['concluidas'] }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-purple-lt">{{ $propriedade->resumo['aplicacoes'] }}</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.propriedades.show', $propriedade) }}" class="btn btn-outline-primary btn-sm">
                                        Acessar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-5 text-center">
                                    <div class="empty">
                                        <div class="empty-icon text-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M3 21l18 0"/><path d="M5 21v-14l8 -4l6 3v15"/><path d="M9 21v-8h4v8"/></svg>
                                        </div>
                                        <p class="empty-title mb-1">Nenhuma propriedade cadastrada ainda</p>
                                        <p class="empty-subtitle text-secondary mb-3">Cadastre sua primeira fazenda para habilitar talhões, culturas e tarefas.</p>
                                        <div class="empty-action">
                                            <a href="{{ route('admin.propriedades.create') }}" class="btn btn-primary btn-sm shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 5l0 14"/><path d="M5 12l14 0"/></svg>
                                                Cadastrar primeira propriedade
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>
@endsection
