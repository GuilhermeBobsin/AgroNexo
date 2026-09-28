@extends('layouts.admin.base')

@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary">Visão geral da operação</div><h1 class="page-title">Painel administrativo</h1></div><div class="col-auto d-flex gap-2"><a href="{{ route('admin.recomendacoes.index', ['status' => 'pendente']) }}" class="btn">Recomendações pendentes <span class="badge bg-yellow-lt ms-1">{{ $recomendacoesPendentes }}</span></a><a href="{{ route('admin.tarefas.create') }}" class="btn btn-primary">Nova tarefa</a></div></div></div>

    <form method="GET" class="card card-body mb-4"><div class="row g-3 align-items-end">
        <div class="col-lg-3"><label class="form-label" for="propriedade_id">Propriedade</label><select class="form-select" name="propriedade_id" id="propriedade_id"><option value="">Todas</option>@foreach ($propriedades as $propriedade)<option value="{{ $propriedade->id }}" @selected(request('propriedade_id') == $propriedade->id)>{{ $propriedade->nome }}</option>@endforeach</select></div>
        <div class="col-lg-3"><label class="form-label" for="responsavel_id">Operador responsável</label><select class="form-select" name="responsavel_id" id="responsavel_id"><option value="">Todos</option>@foreach ($operadores as $operador)<option value="{{ $operador->id }}" @selected(request('responsavel_id') == $operador->id)>{{ $operador->name }}</option>@endforeach</select></div>
        <div class="col-lg-2"><label class="form-label" for="data_inicio">De</label><input class="form-control" type="date" name="data_inicio" id="data_inicio" value="{{ request('data_inicio') }}"></div>
        <div class="col-lg-2"><label class="form-label" for="data_fim">Até</label><input class="form-control" type="date" name="data_fim" id="data_fim" value="{{ request('data_fim') }}"></div>
        <div class="col-lg-2 d-flex gap-2"><button class="btn btn-primary">Filtrar</button><a class="btn" href="{{ route('admin.dashboard') }}">Limpar</a></div>
    </div></form>

    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="row row-cards mb-4">
        @foreach ([['Tarefas no período', $indicadores['tarefas'], 'blue'], ['Pendentes', $indicadores['pendentes'], 'yellow'], ['Em andamento', $indicadores['em_andamento'], 'azure'], ['Concluídas', $indicadores['concluidas'], 'green'], ['Aplicações realizadas', $indicadores['aplicacoes'], 'purple']] as [$label, $value, $color])
            <div class="col-sm-6 col-xl"><div class="card"><div class="card-body"><div class="subheader">{{ $label }}</div><div class="h1 mb-0 text-{{ $color }}">{{ number_format($value, 0, ',', '.') }}</div></div></div></div>
        @endforeach
    </div>

    <div class="row row-cards">
        <div class="col-lg-7"><div class="card"><div class="card-header"><h2 class="card-title">Próximas tarefas</h2><div class="card-actions"><a href="{{ route('admin.tarefas.index') }}">Ver todas</a></div></div>
            <div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Tarefa</th><th>Propriedade / talhão</th><th>Responsável</th><th>Previsão</th><th>Status</th></tr></thead><tbody>
                @forelse ($tarefas as $tarefa)<tr><td><a class="fw-semibold" href="{{ route('admin.tarefas.show', $tarefa) }}">{{ $tarefa->titulo }}</a></td><td>{{ $tarefa->propriedade->nome }}<div class="text-secondary small">{{ $tarefa->talhao?->nome ?? '—' }}</div></td><td>{{ $tarefa->responsavel?->name ?? '—' }}</td><td>{{ $tarefa->data_prevista?->format('d/m/Y') }}</td><td>{{ $tarefa->status === 'em_andamento' ? 'Em andamento' : 'Pendente' }}</td></tr>
                @empty<tr><td colspan="5" class="text-center text-secondary py-4">Nenhuma tarefa aberta para os filtros selecionados.</td></tr>@endforelse
            </tbody></table></div></div></div>
        <div class="col-lg-5"><div class="card"><div class="card-header"><h2 class="card-title">Aplicações recentes</h2><div class="card-actions"><a href="{{ route('admin.aplicacoes.index') }}">Ver registros</a></div></div>
            <div class="list-group list-group-flush">@forelse ($aplicacoes as $aplicacao)<a href="{{ route('admin.aplicacoes.show', $aplicacao) }}" class="list-group-item list-group-item-action"><div class="d-flex justify-content-between"><span class="fw-semibold">{{ $aplicacao->produto?->nome ?? 'Produto removido' }}</span><span class="text-secondary">{{ $aplicacao->data_aplicacao?->format('d/m/Y') }}</span></div><div class="text-secondary small">{{ $aplicacao->talhao?->propriedade?->nome }} · {{ $aplicacao->talhao?->nome }} · {{ $aplicacao->usuario?->name }}</div></a>
                @empty<div class="card-body text-secondary">Nenhuma aplicação encontrada para os filtros selecionados.</div>@endforelse</div></div></div>
    </div>
    <div class="card mt-4"><div class="card-header"><h2 class="card-title">Resumo por propriedade</h2></div>
        <div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Propriedade</th><th>Tarefas</th><th>Pendentes</th><th>Em andamento</th><th>Concluídas</th><th>Aplicações realizadas</th></tr></thead><tbody>
            @forelse ($resumoPropriedades as $propriedade)<tr><td><a href="{{ route('admin.propriedades.show', $propriedade) }}" class="fw-semibold">{{ $propriedade->nome }}</a></td><td>{{ $propriedade->resumo['tarefas'] }}</td><td>{{ $propriedade->resumo['pendentes'] }}</td><td>{{ $propriedade->resumo['em_andamento'] }}</td><td>{{ $propriedade->resumo['concluidas'] }}</td><td>{{ $propriedade->resumo['aplicacoes'] }}</td></tr>
            @empty<tr><td colspan="6" class="text-center text-secondary py-4">Nenhuma propriedade cadastrada.</td></tr>@endforelse
        </tbody></table></div>
    </div>
</div></main>
@endsection
