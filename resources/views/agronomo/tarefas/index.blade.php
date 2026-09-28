@extends('layouts.admin.base')

@section('content')
@php
    $statusLabels = ['pendente' => 'Pendente', 'em_andamento' => 'Em andamento', 'concluida' => 'Concluída', 'cancelada' => 'Cancelada'];
    $statusColors = ['pendente' => 'yellow', 'em_andamento' => 'blue', 'concluida' => 'green', 'cancelada' => 'red'];
    $tipos = ['aplicacao' => 'Aplicação', 'aracao' => 'Aração', 'calagem' => 'Calagem', 'irrigacao' => 'Irrigação', 'manutencao' => 'Manutenção', 'outro' => 'Outro'];
@endphp
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><div class="page-pretitle">Operação</div><h1 class="page-title">Tarefas das propriedades</h1><div class="text-secondary mt-1">Consulte atividades e seus responsáveis nas propriedades vinculadas a você.</div></div></div>
    <form method="GET" class="card card-body mb-3" aria-label="Filtrar tarefas">
        <div class="row g-2 align-items-end">
            <div class="col-sm-6 col-lg-2"><label class="form-label" for="status">Status</label><select class="form-select" id="status" name="status"><option value="">Todos</option>@foreach ($statusLabels as $value => $label)<option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-sm-6 col-lg-2"><label class="form-label" for="tipo">Tipo</label><select class="form-select" id="tipo" name="tipo"><option value="">Todos</option>@foreach ($tipos as $value => $label)<option value="{{ $value }}" @selected(request('tipo') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-sm-6 col-lg-3"><label class="form-label" for="propriedade_id">Propriedade</label><select class="form-select" id="propriedade_id" name="propriedade_id"><option value="">Todas</option>@foreach ($propriedades as $propriedade)<option value="{{ $propriedade->id }}" @selected(request('propriedade_id') == $propriedade->id)>{{ $propriedade->nome }}</option>@endforeach</select></div>
            <div class="col-sm-6 col-lg-2"><label class="form-label" for="data_inicio">De</label><input class="form-control" type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}"></div>
            <div class="col-sm-6 col-lg-2"><label class="form-label" for="data_fim">Até</label><input class="form-control" type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}"></div>
            <div class="col-sm-6 col-lg-1 d-flex gap-2"><button class="btn btn-primary">Filtrar</button></div>
        </div>
        @if (request()->query())<div class="mt-2"><a class="small" href="{{ route('agronomo.tarefas.index') }}">Limpar filtros</a></div>@endif
    </form>
    <div class="card"><div class="table-responsive"><table class="table table-vcenter card-table">
        <thead><tr><th>Tarefa</th><th>Propriedade / talhão</th><th>Responsável / recurso</th><th>Previsão</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($tarefas as $tarefa)
                <tr>
                    <td><a class="fw-medium" href="{{ route('agronomo.tarefas.show', $tarefa) }}">{{ $tarefa->titulo }}</a><div class="text-secondary small">{{ $tipos[$tarefa->tipo] ?? $tarefa->tipo }}</div></td>
                    <td>{{ $tarefa->propriedade->nome }}<div class="text-secondary small">{{ $tarefa->talhao->nome ?? 'Sem talhão' }}</div></td>
                    <td>{{ $tarefa->responsavel->name }}<div class="text-secondary small">{{ $tarefa->recurso->nome ?? 'Sem recurso' }}</div></td>
                    <td>{{ $tarefa->data_prevista->format('d/m/Y') }}{{ $tarefa->hora_prevista ? ' · '.substr($tarefa->hora_prevista, 0, 5) : '' }}</td>
                    <td><span class="badge bg-{{ $statusColors[$tarefa->status] ?? 'secondary' }}-lt">{{ $statusLabels[$tarefa->status] ?? $tarefa->status }}</span></td>
                    <td><a class="btn btn-sm" href="{{ route('agronomo.tarefas.show', $tarefa) }}">Detalhes</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-secondary py-5">Nenhuma tarefa encontrada para as propriedades e filtros selecionados.</td></tr>
            @endforelse
        </tbody>
    </table></div></div>
    <div class="mt-3">{{ $tarefas->links() }}</div>
</div></main>
@endsection
