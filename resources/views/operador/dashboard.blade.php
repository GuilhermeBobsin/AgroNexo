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

        {{-- Hero Header do Operador --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center justify-content-between g-3">
                <div class="col-12 col-md">
                    <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <span class="badge bg-blue-lt text-uppercase fw-bold letter-spacing-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M9 11l3 3l8 -8"/><path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"/></svg>
                            Operação de Campo & Execução
                        </span>
                        <span class="text-secondary small d-none d-sm-inline">·</span>
                        <span class="text-secondary small d-none d-sm-inline">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12z"/><path d="M16 3v4"/><path d="M8 3v4"/><path d="M4 11h16"/></svg>
                            {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
                        </span>
                    </div>
                    <h1 class="page-title fs-2 mb-1">
                        Minhas tarefas
                    </h1>
                    <p class="text-secondary mb-0">
                        Olá, <strong>{{ auth()->user()->name }}</strong>. Atualize o andamento e registre os dados da aplicação ao concluir suas atividades.
                    </p>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('operador.tarefas.index') }}" class="btn btn-primary shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><path d="M9 14l2 2l4 -4"/></svg>
                            Abrir lista de tarefas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Alerta de Tarefas Atrasadas (quando houver) --}}
        @if (isset($indicadores['atrasadas']) && $indicadores['atrasadas'] > 0)
            <div class="alert alert-danger shadow-sm mb-4" role="alert">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <div class="d-flex align-items-center">
                        <span class="avatar avatar-sm bg-danger text-danger-fg me-3 rounded-circle shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 9v4"/><path d="M12 16h.01"/></svg>
                        </span>
                        <div>
                            <div class="fw-bold mb-1">Atenção: Tarefas com prazo vencido</div>
                            <div class="text-secondary small">
                                Você possui <strong>{{ $indicadores['atrasadas'] }}</strong> atividade(s) atribuída(s) com data prevista anterior a hoje.
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('operador.tarefas.index', ['status' => 'pendente']) }}" class="btn btn-danger btn-sm shadow-sm">
                        Ver tarefas pendentes
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M5 12l14 0"/><path d="M13 18l6 -6"/><path d="M13 6l6 6"/></svg>
                    </a>
                </div>
            </div>
        @endif

        {{-- Indicadores / KPIs Modernos --}}
        <div class="row row-deck row-cards mb-4">

            {{-- Pendentes (Aguardando Início) --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-hover-effect shadow-sm border-start border-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-secondary small fw-semibold text-uppercase">Aguardando início</div>
                            <span class="avatar avatar-sm bg-yellow-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M12 7v5l3 3" />
                                </svg>
                            </span>
                        </div>
                        <div class="h1 fw-bold mb-1 text-warning">{{ number_format($indicadores['pendentes'], 0, ',', '.') }}</div>
                        <div class="text-secondary small">
                            Tarefas pendentes de execução
                        </div>
                    </div>
                </div>
            </div>

            {{-- Em Andamento --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-hover-effect shadow-sm border-start border-3 border-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-secondary small fw-semibold text-uppercase">Em andamento</div>
                            <span class="avatar avatar-sm bg-azure-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                </svg>
                            </span>
                        </div>
                        <div class="h1 fw-bold mb-1 text-azure">{{ number_format($indicadores['em_andamento'], 0, ',', '.') }}</div>
                        <div class="text-secondary small">
                            Atividades em execução no campo
                        </div>
                    </div>
                </div>
            </div>

            {{-- Concluídas no Mês --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-hover-effect shadow-sm border-start border-3 border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-secondary small fw-semibold text-uppercase">Concluídas neste mês</div>
                            <span class="avatar avatar-sm bg-green-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M9 12l2 2l4 -4" />
                                </svg>
                            </span>
                        </div>
                        <div class="h1 fw-bold mb-1 text-green">{{ number_format($indicadores['concluidas_mes'], 0, ',', '.') }}</div>
                        <div class="text-secondary small">
                            Finalizadas no mês atual
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Atribuído --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card card-hover-effect shadow-sm border-start border-3 border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="text-secondary small fw-semibold text-uppercase">Total atribuído</div>
                            <span class="avatar avatar-sm bg-primary-lt rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/>
                                    <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"/>
                                    <path d="M9 14l2 2l4 -4"/>
                                </svg>
                            </span>
                        </div>
                        <div class="h1 fw-bold mb-1">{{ number_format($indicadores['total'] ?? ($indicadores['pendentes'] + $indicadores['em_andamento'] + $indicadores['concluidas_mes']), 0, ',', '.') }}</div>
                        <div class="text-secondary small">
                            Histórico de tarefas
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Próximas Atividades --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header py-3 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm bg-primary-lt rounded">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><path d="M9 14l2 2l4 -4"/></svg>
                    </span>
                    <div>
                        <h2 class="card-title mb-0">Próximas atividades</h2>
                        <div class="text-secondary small">Tarefas atribuídas a você com prazo mais próximo</div>
                    </div>
                </div>
                <div class="card-actions">
                    <a href="{{ route('operador.tarefas.index') }}" class="btn btn-outline-primary btn-sm">
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
                            <th>Data prevista</th>
                            <th>Status</th>
                            <th class="text-end">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($proximasTarefas as $tarefa)
                            @php
                                $statusColor = $tarefa->status === 'em_andamento' ? 'azure' : 'yellow';
                                $statusLabel = $tarefa->status === 'em_andamento' ? 'Em andamento' : 'Pendente';
                                $previsao = $tarefa->data_prevista;
                                $atrasada = $previsao && $previsao->isPast() && !$previsao->isToday();
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="status-dot status-{{ $statusColor }} me-2"></span>
                                        <div>
                                            <a class="fw-bold text-reset text-decoration-none" href="{{ route('operador.tarefas.show', $tarefa) }}">
                                                {{ $tarefa->titulo }}
                                            </a>
                                            <div class="mt-1">
                                                <span class="badge bg-secondary-lt text-capitalize py-0 px-1">{{ ucfirst(str_replace('_', ' ', $tarefa->tipo)) }}</span>
                                                @if ($tarefa->produto)
                                                    <span class="badge bg-purple-lt py-0 px-1">{{ $tarefa->produto->nome }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $tarefa->propriedade->nome }}</div>
                                    <div class="text-secondary small">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1"><path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5"/><path d="M9 4v13"/></svg>
                                        {{ $tarefa->talhao?->nome ?? '—' }}
                                    </div>
                                </td>
                                <td>
                                    @if ($previsao)
                                        <div class="small {{ $atrasada ? 'text-danger fw-bold' : ($previsao->isToday() ? 'text-warning fw-bold' : 'text-secondary') }}">
                                            {{ $previsao->format('d/m/Y') }}
                                            @if ($tarefa->hora_prevista)
                                                · {{ substr($tarefa->hora_prevista, 0, 5) }}
                                            @endif
                                            @if ($atrasada)
                                                <span class="badge bg-danger-lt ms-1">Atrasada</span>
                                            @elseif ($previsao->isToday())
                                                <span class="badge bg-warning-lt ms-1">Hoje</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-secondary small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $statusColor }}-lt">{{ $statusLabel }}</span>
                                </td>
                                <td class="text-end">
                                    <a class="btn btn-sm btn-outline-primary" href="{{ route('operador.tarefas.show', $tarefa) }}">
                                        Abrir
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon ms-1"><path d="M9 6l6 6l-6 6"/></svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-secondary py-5">
                                    <div class="empty">
                                        <div class="empty-icon text-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"/><path d="M9 14l2 2l4 -4"/></svg>
                                        </div>
                                        <p class="empty-title mb-1">Você não tem tarefas abertas no momento.</p>
                                        <p class="empty-subtitle text-secondary mb-0">Todas as atividades atribuídas a você estão concluídas!</p>
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
