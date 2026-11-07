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
                                <path d="M4 4h6v8h-6z" />
                                <path d="M4 16h6v4h-6z" />
                                <path d="M14 12h6v8h-6z" />
                                <path d="M14 4h6v4h-6z" />
                            </svg>
                        </span>
                        <div>
                            <div class="text-secondary">Visão geral da operação</div>
                            <h1 class="page-title mb-0">Painel administrativo</h1>
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.recomendacoes.index', ['status' => 'pendente']) }}" class="btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-1">
                                <path d="M12 9v4" />
                                <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                <path d="M12 16h.01" />
                            </svg>
                            Recomendações pendentes
                            <span class="badge bg-yellow-lt ms-1">{{ $recomendacoesPendentes }}</span>
                        </a>
                        <a href="{{ route('admin.tarefas.create') }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Nova tarefa
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="card mb-4">
            <div class="card-header">
                <span class="avatar avatar-sm bg-secondary-lt me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                        <path d="M12 6a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M4 6l8 0" />
                        <path d="M16 6l4 0" />
                        <path d="M6 12a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M4 12l2 0" />
                        <path d="M10 12l10 0" />
                        <path d="M15 18a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                        <path d="M4 18l11 0" />
                        <path d="M19 18l1 0" />
                    </svg>
                </span>
                <h3 class="card-title">Filtros</h3>
            </div>
            <form method="GET" class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-lg-3">
                        <label class="form-label" for="propriedade_id">Propriedade</label>
                        <select class="form-select" name="propriedade_id" id="propriedade_id">
                            <option value="">Todas</option>
                            @foreach ($propriedades as $propriedade)
                                <option value="{{ $propriedade->id }}" @selected(request('propriedade_id') == $propriedade->id)>{{ $propriedade->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <label class="form-label" for="responsavel_id">Operador responsável</label>
                        <select class="form-select" name="responsavel_id" id="responsavel_id">
                            <option value="">Todos</option>
                            @foreach ($operadores as $operador)
                                <option value="{{ $operador->id }}" @selected(request('responsavel_id') == $operador->id)>{{ $operador->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label" for="data_inicio">De</label>
                        <input class="form-control" type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}">
                    </div>
                    <div class="col-lg-2">
                        <label class="form-label" for="data_fim">Até</label>
                        <input class="form-control" type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}">
                    </div>
                    <div class="col-lg-2 d-flex gap-2">
                        <button class="btn btn-primary w-100">Filtrar</button>
                        <a class="btn w-100" href="{{ route('admin.dashboard') }}">Limpar</a>
                    </div>
                </div>
            </form>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible" role="alert">
                <div class="alert-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon">
                        <path d="M12 9v4" />
                        <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                        <path d="M12 16h.01" />
                    </svg>
                </div>
                <div>{{ $errors->first() }}</div>
                <a href="#" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></a>
            </div>
        @endif

        {{-- Indicadores --}}
        <div class="row row-deck row-cards mb-4">

            <div class="col-sm-6 col-xl">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-blue-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12" />
                                    <path d="M16 3l0 4" />
                                    <path d="M8 3l0 4" />
                                    <path d="M4 11l16 0" />
                                </svg>
                            </span>
                            <div>
                                <div class="subheader">Tarefas no período</div>
                                <div class="h1 mb-0">{{ number_format($indicadores['tarefas'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-yellow-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M12 7v5l3 3" />
                                </svg>
                            </span>
                            <div>
                                <div class="subheader">Pendentes</div>
                                <div class="h1 mb-0 text-yellow">{{ number_format($indicadores['pendentes'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-azure-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M20 11a8.1 8.1 0 0 0 -15.5 -2m-.5 -4v4h4" />
                                    <path d="M4 13a8.1 8.1 0 0 0 15.5 2m.5 4v-4h-4" />
                                </svg>
                            </span>
                            <div>
                                <div class="subheader">Em andamento</div>
                                <div class="h1 mb-0 text-azure">{{ number_format($indicadores['em_andamento'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-green-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                    <path d="M9 12l2 2l4 -4" />
                                </svg>
                            </span>
                            <div>
                                <div class="subheader">Concluídas</div>
                                <div class="h1 mb-0 text-green">{{ number_format($indicadores['concluidas'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-purple-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                </svg>
                            </span>
                            <div>
                                <div class="subheader">Aplicações realizadas</div>
                                <div class="h1 mb-0 text-purple">{{ number_format($indicadores['aplicacoes'], 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Próximas tarefas + Aplicações recentes --}}
        <div class="row row-cards">

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <span class="avatar avatar-sm bg-blue-lt me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12" />
                                <path d="M16 3l0 4" />
                                <path d="M8 3l0 4" />
                                <path d="M4 11l16 0" />
                            </svg>
                        </span>
                        <h2 class="card-title">Próximas tarefas</h2>
                        <div class="card-actions">
                            <a href="{{ route('admin.tarefas.index') }}">Ver todas</a>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Tarefa</th>
                                    <th>Propriedade / talhão</th>
                                    <th>Responsável</th>
                                    <th>Previsão</th>
                                    <th>Status</th>
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
                                    @endphp
                                    <tr>
                                        <td>
                                            <a class="fw-semibold" href="{{ route('admin.tarefas.show', $tarefa) }}">{{ $tarefa->titulo }}</a>
                                        </td>
                                        <td>
                                            {{ $tarefa->propriedade->nome }}
                                            <div class="text-secondary small">{{ $tarefa->talhao?->nome ?? '—' }}</div>
                                        </td>
                                        <td>{{ $tarefa->responsavel?->name ?? '—' }}</td>
                                        <td>{{ $tarefa->data_prevista?->format('d/m/Y') }}</td>
                                        <td><span class="badge bg-{{ $corStatus }}-lt">{{ $rotuloStatus }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="empty py-4">
                                                <p class="empty-title mb-0 text-secondary">Nenhuma tarefa aberta para os filtros selecionados.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <span class="avatar avatar-sm bg-purple-lt me-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                            </svg>
                        </span>
                        <h2 class="card-title">Aplicações recentes</h2>
                        <div class="card-actions">
                            <a href="{{ route('admin.aplicacoes.index') }}">Ver registros</a>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse ($aplicacoes as $aplicacao)
                            <a href="{{ route('admin.aplicacoes.show', $aplicacao) }}" class="list-group-item list-group-item-action">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">{{ $aplicacao->produto?->nome ?? 'Produto removido' }}</span>
                                    <span class="text-secondary">{{ $aplicacao->data_aplicacao?->format('d/m/Y') }}</span>
                                </div>
                                <div class="text-secondary small">
                                    {{ $aplicacao->talhao?->propriedade?->nome }} · {{ $aplicacao->talhao?->nome }} · {{ $aplicacao->usuario?->name }}
                                </div>
                            </a>
                        @empty
                            <div class="card-body">
                                <div class="empty py-4">
                                    <p class="empty-title mb-0 text-secondary">Nenhuma aplicação encontrada para os filtros selecionados.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>

        {{-- Resumo por propriedade --}}
        <div class="card mt-4">
            <div class="card-header">
                <span class="avatar avatar-sm bg-green-lt me-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                        <path d="M3 21l18 0"></path>
                        <path d="M5 21v-14l8 -4l6 3v15"></path>
                        <path d="M9 21v-8h4v8"></path>
                    </svg>
                </span>
                <h2 class="card-title">Resumo por propriedade</h2>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Propriedade</th>
                            <th>Tarefas</th>
                            <th>Pendentes</th>
                            <th>Em andamento</th>
                            <th>Concluídas</th>
                            <th>Aplicações realizadas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($resumoPropriedades as $propriedade)
                            <tr>
                                <td><a href="{{ route('admin.propriedades.show', $propriedade) }}" class="fw-semibold">{{ $propriedade->nome }}</a></td>
                                <td>{{ $propriedade->resumo['tarefas'] }}</td>
                                <td><span class="badge bg-yellow-lt">{{ $propriedade->resumo['pendentes'] }}</span></td>
                                <td><span class="badge bg-azure-lt">{{ $propriedade->resumo['em_andamento'] }}</span></td>
                                <td><span class="badge bg-green-lt">{{ $propriedade->resumo['concluidas'] }}</span></td>
                                <td>{{ $propriedade->resumo['aplicacoes'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty py-4">
                                        <p class="empty-title mb-0 text-secondary">Nenhuma propriedade cadastrada.</p>
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