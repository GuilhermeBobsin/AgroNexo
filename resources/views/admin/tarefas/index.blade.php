@extends('layouts.admin.base')

@section('content')
@php $statusLabels = ['pendente' => 'Pendente', 'em_andamento' => 'Em andamento', 'concluida' => 'Concluída', 'cancelada' => 'Cancelada']; $statusColors = ['pendente' => 'yellow', 'em_andamento' => 'blue', 'concluida' => 'green', 'cancelada' => 'red']; @endphp
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header d-print-none mb-4"><div class="row align-items-center"><div class="col"><h2 class="page-title">Tarefas</h2><div class="text-secondary mt-1">Acompanhe e distribua as atividades da operação.</div></div><div class="col-auto ms-auto"><a href="{{ route('admin.tarefas.create') }}" class="btn btn-primary">+ Nova tarefa</a></div></div></div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if (session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
    <div class="row row-cards mb-4">
        @foreach ([['Tarefas pendentes', $pendentes, 'yellow'], ['Previstas para hoje', $hoje, 'blue']] as [$titulo, $valor, $cor])<div class="col-sm-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-secondary">{{ $titulo }}</div><div class="h2 mb-0"><span class="text-{{ $cor }}">{{ $valor }}</span></div></div></div></div>@endforeach
    </div>
    <form method="GET" class="mb-3"><div class="row g-2"><div class="col-auto"><select name="status" class="form-select"><option value="">Todos os status</option>@foreach ($statusLabels as $valor => $label)<option value="{{ $valor }}" @selected(request('status') === $valor)>{{ $label }}</option>@endforeach</select></div><div class="col-auto"><button class="btn">Filtrar</button></div></div></form>
    <div class="card"><div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Tarefa</th><th>Data prevista</th><th>Propriedade / talhão</th><th>Responsável</th><th>Recurso</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse ($tarefas as $tarefa)<tr><td><a class="fw-bold" href="{{ route('admin.tarefas.show', $tarefa) }}">{{ $tarefa->titulo }}</a><div class="text-secondary small">{{ ucfirst(str_replace('_', ' ', $tarefa->tipo)) }}</div></td><td>{{ $tarefa->data_prevista->format('d/m/Y') }}{{ $tarefa->hora_prevista ? ' · '.substr($tarefa->hora_prevista, 0, 5) : '' }}</td><td>{{ $tarefa->propriedade->nome }}<div class="text-secondary small">{{ $tarefa->talhao->nome ?? 'Sem talhão' }}</div></td><td>{{ $tarefa->responsavel->name }}</td><td>{{ $tarefa->recurso->nome ?? '—' }}</td><td><span class="badge bg-{{ $statusColors[$tarefa->status] ?? 'secondary' }}-lt">{{ $statusLabels[$tarefa->status] ?? $tarefa->status }}</span></td><td><div class="btn-list flex-nowrap"><a href="{{ route('admin.tarefas.show', $tarefa) }}" class="btn btn-sm">Ver</a>@if ($tarefa->status === 'pendente')<a href="{{ route('admin.tarefas.edit', $tarefa) }}" class="btn btn-sm">Editar</a><form method="POST" action="{{ route('admin.tarefas.destroy', $tarefa) }}" onsubmit="return confirm('Excluir esta tarefa pendente?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Excluir</button></form>@endif</div></td></tr>
        @empty<tr><td colspan="7" class="text-center text-secondary py-5">Nenhuma tarefa encontrada.</td></tr>@endforelse
    </tbody></table></div></div>
    <div class="mt-3">{{ $tarefas->links() }}</div>
</div></main>
@endsection
