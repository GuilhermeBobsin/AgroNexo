@extends('layouts.admin.base')

@section('content')
@php $statusLabels = ['pendente' => 'Pendente', 'em_andamento' => 'Em andamento', 'concluida' => 'Concluída', 'cancelada' => 'Cancelada']; $statusColors = ['pendente' => 'yellow', 'em_andamento' => 'blue', 'concluida' => 'green', 'cancelada' => 'red']; @endphp
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h2 class="page-title">Minhas tarefas</h2><div class="text-secondary mt-1">Consulte as atividades atribuídas a você.</div></div></div>
    <form method="GET" class="mb-3"><div class="row g-2"><div class="col-auto"><select name="status" class="form-select"><option value="">Todos os status</option>@foreach ($statusLabels as $valor => $label)<option value="{{ $valor }}" @selected(request('status') === $valor)>{{ $label }}</option>@endforeach</select></div><div class="col-auto"><button class="btn">Filtrar</button></div></div></form>
    <div class="card"><div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Tarefa</th><th>Data prevista</th><th>Propriedade / talhão</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse ($tarefas as $tarefa)<tr><td><a class="fw-bold" href="{{ route('operador.tarefas.show', $tarefa) }}">{{ $tarefa->titulo }}</a><div class="text-secondary small">{{ ucfirst(str_replace('_', ' ', $tarefa->tipo)) }}</div></td><td>{{ $tarefa->data_prevista->format('d/m/Y') }}{{ $tarefa->hora_prevista ? ' · '.substr($tarefa->hora_prevista, 0, 5) : '' }}</td><td>{{ $tarefa->propriedade->nome }}<div class="text-secondary small">{{ $tarefa->talhao->nome ?? 'Sem talhão' }}</div></td><td><span class="badge bg-{{ $statusColors[$tarefa->status] ?? 'secondary' }}-lt">{{ $statusLabels[$tarefa->status] ?? $tarefa->status }}</span></td><td><a href="{{ route('operador.tarefas.show', $tarefa) }}" class="btn btn-sm">Abrir</a></td></tr>
        @empty<tr><td colspan="5" class="text-center text-secondary py-5">Você não tem tarefas atribuídas.</td></tr>@endforelse
    </tbody></table></div></div><div class="mt-3">{{ $tarefas->links() }}</div>
</div></main>
@endsection
