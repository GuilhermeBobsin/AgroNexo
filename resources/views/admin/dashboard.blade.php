@extends('layouts.admin.base')

@section('content')
<main class="page-body admin-dashboard">
    <div class="container-xl">
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center g-3">
                <div class="col">
                    <div class="page-pretitle">AgroNexo · Visão geral</div>
                    <h1 class="page-title">Dashboard</h1>
                    <div class="text-secondary mt-1">Acompanhe as atividades e os resultados das suas propriedades.</div>
                </div>
                <div class="col-auto ms-auto d-flex flex-wrap gap-2">
                    <a class="btn" href="{{ route('admin.recomendacoes.index', ['status' => 'pendente']) }}">
                        Recomendações <span class="badge bg-yellow-lt ms-1">{{ $recomendacoesPendentes }}</span>
                    </a>
                    <a class="btn btn-primary" href="{{ route('admin.tarefas.create') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                        Nova tarefa
                    </a>
                </div>
            </div>
        </div>

        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
        @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

        <section class="card dashboard-welcome mb-4" aria-label="Resumo da conta">
            <div class="card-body p-4 p-lg-5">
                <div class="row align-items-center g-4">
                    <div class="col-lg-7">
                        <div class="text-uppercase small fw-bold opacity-75 mb-2">Painel do produtor</div>
                        <h2 class="display-6 fw-bold mb-2">Olá, {{ auth()->user()->name }}!</h2>
                        <p class="mb-4 opacity-75">Aqui está um resumo atualizado da operação da sua fazenda.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('admin.propriedades.index') }}" class="btn btn-light text-primary">Ver propriedades</a>
                            <a href="{{ route('admin.estoque.index') }}" class="btn btn-outline-light">Consultar estoque</a>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="welcome-summary">
                            <div class="d-flex justify-content-between align-items-center mb-3"><span>Atividades em aberto</span><span class="badge bg-white-lt">{{ number_format($indicadores['pendentes'] + $indicadores['em_andamento'], 0, ',', '.') }}</span></div>
                            <div class="progress progress-sm bg-white-lt"><div class="progress-bar bg-white" style="width: {{ $indicadores['tarefas'] > 0 ? round(($indicadores['concluidas'] / $indicadores['tarefas']) * 100) : 0 }}%" role="progressbar" aria-valuenow="{{ $indicadores['tarefas'] > 0 ? round(($indicadores['concluidas'] / $indicadores['tarefas']) * 100) : 0 }}" aria-valuemin="0" aria-valuemax="100"></div></div>
                            <div class="small opacity-75 mt-2">{{ number_format($indicadores['concluidas'], 0, ',', '.') }} de {{ number_format($indicadores['tarefas'], 0, ',', '.') }} tarefas concluídas no período filtrado</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <form method="GET" action="{{ route('admin.dashboard') }}" class="card card-body dashboard-filters mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-sm-6 col-xl-3"><label class="form-label" for="propriedade_id">Propriedade</label><select class="form-select" name="propriedade_id" id="propriedade_id"><option value="">Todas as minhas propriedades</option>@foreach ($propriedades as $propriedade)<option value="{{ $propriedade->id }}" @selected(request('propriedade_id') == $propriedade->id)>{{ $propriedade->nome }}</option>@endforeach</select></div>
                <div class="col-sm-6 col-xl-3"><label class="form-label" for="responsavel_id">Operador</label><select class="form-select" name="responsavel_id" id="responsavel_id"><option value="">Todos</option>@foreach ($operadores as $operador)<option value="{{ $operador->id }}" @selected(request('responsavel_id') == $operador->id)>{{ $operador->name }}</option>@endforeach</select></div>
                <div class="col-sm-6 col-xl-2"><label class="form-label" for="data_inicio">De</label><input class="form-control" type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}"></div>
                <div class="col-sm-6 col-xl-2"><label class="form-label" for="data_fim">Até</label><input class="form-control" type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}"></div>
                <div class="col-xl-2 d-flex gap-2"><button class="btn btn-primary flex-fill">Filtrar</button><a class="btn" href="{{ route('admin.dashboard') }}">Limpar</a></div>
            </div>
        </form>

        <div class="row row-cards mb-4">
            @foreach ([['Tarefas no período', $indicadores['tarefas'], 'blue', 'clipboard-list'], ['Pendentes', $indicadores['pendentes'], 'yellow', 'clock'], ['Em andamento', $indicadores['em_andamento'], 'azure', 'tool'], ['Concluídas', $indicadores['concluidas'], 'green', 'circle-check'], ['Aplicações', $indicadores['aplicacoes'], 'purple', 'droplet']] as [$label, $value, $color, $icon])
                <div class="col-6 col-md-4 col-xl">
                    <article class="card dashboard-stat h-100"><div class="card-body">
                        <div class="d-flex align-items-center justify-content-between"><div class="subheader">{{ $label }}</div><span class="avatar avatar-sm bg-{{ $color }}-lt text-{{ $color }}"><i class="ti ti-{{ $icon }}"></i></span></div>
                        <div class="h1 mb-0 mt-3">{{ number_format($value, 0, ',', '.') }}</div>
                    </div></article>
                </div>
            @endforeach
        </div>

        <div class="row row-cards">
            <div class="col-xl-7">
                <section class="card h-100">
                    <div class="card-header"><div><h2 class="card-title">Próximas tarefas</h2><div class="text-secondary small">Atividades pendentes ou em execução</div></div><div class="card-actions"><a href="{{ route('admin.tarefas.index') }}">Ver todas</a></div></div>
                    <div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Atividade</th><th>Fazenda / talhão</th><th>Responsável</th><th>Previsão</th><th>Status</th></tr></thead><tbody>
                        @forelse ($tarefas as $tarefa)
                            <tr><td><a class="fw-semibold text-reset" href="{{ route('admin.tarefas.show', $tarefa) }}">{{ $tarefa->titulo }}</a><div class="text-secondary small">{{ ucfirst(str_replace('_', ' ', $tarefa->tipo)) }}</div></td><td>{{ $tarefa->propriedade->nome }}<div class="text-secondary small">{{ $tarefa->talhao?->nome ?? 'Todos os talhões' }}</div></td><td>{{ $tarefa->responsavel?->name ?? '—' }}</td><td>{{ $tarefa->data_prevista?->format('d/m/Y') }}{{ $tarefa->hora_prevista ? ' · '.substr($tarefa->hora_prevista, 0, 5) : '' }}</td><td><span class="badge bg-{{ $tarefa->status === 'em_andamento' ? 'blue' : 'yellow' }}-lt">{{ $tarefa->status === 'em_andamento' ? 'Em andamento' : 'Pendente' }}</span></td></tr>
                        @empty<tr><td colspan="5" class="text-center text-secondary py-5">Nenhuma tarefa em aberto para os filtros selecionados.</td></tr>@endforelse
                    </tbody></table></div>
                </section>
            </div>
            <div class="col-xl-5">
                <section class="card h-100">
                    <div class="card-header"><div><h2 class="card-title">Aplicações recentes</h2><div class="text-secondary small">Registros realizados pela equipe</div></div><div class="card-actions"><a href="{{ route('admin.aplicacoes.index') }}">Ver todas</a></div></div>
                    <div class="list-group list-group-flush">
                        @forelse ($aplicacoes as $aplicacao)
                            <a href="{{ route('admin.aplicacoes.show', $aplicacao) }}" class="list-group-item list-group-item-action"><div class="d-flex align-items-start justify-content-between gap-3"><div><div class="fw-semibold">{{ $aplicacao->produto?->nome ?? 'Produto removido' }}</div><div class="text-secondary small mt-1">{{ $aplicacao->talhao?->propriedade?->nome }} · {{ $aplicacao->talhao?->nome }}<br>Por {{ $aplicacao->usuario?->name ?? '—' }}</div></div><div class="text-end text-nowrap"><span class="badge bg-green-lt">Realizada</span><div class="small text-secondary mt-2">{{ $aplicacao->data_aplicacao?->format('d/m/Y') }}</div></div></div></a>
                        @empty<div class="card-body text-center text-secondary py-5">Nenhuma aplicação encontrada no período.</div>@endforelse
                    </div>
                </section>
            </div>
        </div>

        <section class="card mt-4">
            <div class="card-header"><div><h2 class="card-title">Resumo por propriedade</h2><div class="text-secondary small">Indicadores das suas fazendas</div></div><div class="card-actions"><a href="{{ route('admin.propriedades.index') }}">Gerenciar propriedades</a></div></div>
            <div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Propriedade</th><th>Tarefas</th><th>Pendentes</th><th>Em andamento</th><th>Concluídas</th><th>Aplicações</th></tr></thead><tbody>
                @forelse ($resumoPropriedades as $propriedade)<tr><td><a href="{{ route('admin.propriedades.show', $propriedade) }}" class="fw-semibold">{{ $propriedade->nome }}</a></td><td>{{ number_format($propriedade->resumo['tarefas'], 0, ',', '.') }}</td><td><span class="badge bg-yellow-lt">{{ number_format($propriedade->resumo['pendentes'], 0, ',', '.') }}</span></td><td><span class="badge bg-blue-lt">{{ number_format($propriedade->resumo['em_andamento'], 0, ',', '.') }}</span></td><td><span class="badge bg-green-lt">{{ number_format($propriedade->resumo['concluidas'], 0, ',', '.') }}</span></td><td>{{ number_format($propriedade->resumo['aplicacoes'], 0, ',', '.') }}</td></tr>
                @empty<tr><td colspan="6" class="text-center text-secondary py-5">Cadastre sua primeira propriedade para acompanhar os indicadores aqui. <a href="{{ route('admin.propriedades.create') }}">Cadastrar propriedade</a></td></tr>@endforelse
            </tbody></table></div>
        </section>
    </div>
</main>
<style>
    .dashboard-welcome { color: #fff; border: 0; overflow: hidden; background: radial-gradient(circle at 82% 18%, rgba(255,255,255,.22), transparent 27%), linear-gradient(120deg, #146c43 0%, #198754 55%, #28a879 100%); box-shadow: 0 1rem 2.5rem rgba(25,135,84,.16); }
    .dashboard-welcome .btn-outline-light { color: #fff; border-color: rgba(255,255,255,.55); }
    .dashboard-welcome .btn-outline-light:hover { color: #146c43; background: #fff; }
    .welcome-summary { padding: 1.25rem; border: 1px solid rgba(255,255,255,.25); border-radius: .75rem; background: rgba(0,0,0,.12); backdrop-filter: blur(8px); }
    .dashboard-stat { transition: transform .18s ease, box-shadow .18s ease; }
    .dashboard-stat:hover { transform: translateY(-2px); box-shadow: var(--tblr-box-shadow-card); }
    .admin-dashboard .card-title { font-weight: 650; }
    @media (max-width: 575.98px) { .dashboard-welcome .display-6 { font-size: 1.75rem; } }
</style>
@endsection
