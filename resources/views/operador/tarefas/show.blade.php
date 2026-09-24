@extends('layouts.admin.base')

@section('content')
@php $statusLabels = ['pendente' => 'Pendente', 'em_andamento' => 'Em andamento', 'concluida' => 'Concluída', 'cancelada' => 'Cancelada']; @endphp
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary mb-1"><a href="{{ route('operador.tarefas.index') }}">Minhas tarefas</a> / Detalhes</div><h2 class="page-title">{{ $tarefa->titulo }}</h2></div><div class="col-auto ms-auto"><span class="badge bg-blue-lt">{{ $statusLabels[$tarefa->status] ?? $tarefa->status }}</span></div></div></div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row row-cards"><div class="col-lg-8"><div class="card"><div class="card-header"><h3 class="card-title">Instruções da atividade</h3></div><div class="card-body"><div class="datagrid">
        <div class="datagrid-item"><div class="datagrid-title">Tipo</div><div class="datagrid-content">{{ ucfirst(str_replace('_', ' ', $tarefa->tipo)) }}</div></div><div class="datagrid-item"><div class="datagrid-title">Propriedade</div><div class="datagrid-content">{{ $tarefa->propriedade->nome }}</div></div><div class="datagrid-item"><div class="datagrid-title">Talhão</div><div class="datagrid-content">{{ $tarefa->talhao->nome ?? '—' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Recurso</div><div class="datagrid-content">{{ $tarefa->recurso->nome ?? 'Não há recurso associado' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Data prevista</div><div class="datagrid-content">{{ $tarefa->data_prevista->format('d/m/Y') }} {{ $tarefa->hora_prevista ? substr($tarefa->hora_prevista, 0, 5) : '' }}</div></div>
        @if ($tarefa->produto)<div class="datagrid-item"><div class="datagrid-title">Produto / dose</div><div class="datagrid-content">{{ $tarefa->produto->nome }} · {{ $tarefa->dose }}</div></div>@endif
        <div class="datagrid-item"><div class="datagrid-title">Orientações</div><div class="datagrid-content">{{ $tarefa->observacoes ?: 'Sem orientações adicionais.' }}</div></div>
    </div></div>
    @if (in_array($tarefa->status, ['pendente', 'em_andamento'], true))<div class="card-footer d-flex gap-2">
        @if ($tarefa->status === 'pendente')<form method="POST" action="{{ route('operador.tarefas.status', $tarefa) }}" data-ajax-form class="status-form">@csrf @method('PATCH')<input type="hidden" name="status" value="em_andamento"><button type="submit" class="btn btn-primary">Iniciar tarefa</button></form>@endif
        @if ($tarefa->status === 'em_andamento')<form method="POST" action="{{ route('operador.tarefas.status', $tarefa) }}" data-ajax-form class="status-form">@csrf @method('PATCH')<input type="hidden" name="status" value="concluida"><button type="submit" class="btn btn-success">Marcar como concluída</button></form><form method="POST" action="{{ route('operador.tarefas.status', $tarefa) }}" data-ajax-form class="status-form">@csrf @method('PATCH')<input type="hidden" name="status" value="cancelada"><button type="submit" class="btn btn-outline-danger">Cancelar tarefa</button></form>@endif
    </div>@endif
    </div></div></div>
</div></main>
<script type="module">
    document.querySelectorAll('.status-form').forEach((form) => form.addEventListener('ajax-success', (event) => event.detail.toastPromise.then(() => window.location.reload())));
</script>
@endsection
