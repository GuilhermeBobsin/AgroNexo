@extends('layouts.admin.base')

@section('content')
@php
    $statusLabels = ['pendente' => 'Aguardando análise', 'tarefa_criada' => 'Tarefa criada', 'recusada' => 'Recusada'];
    $statusColors = ['pendente' => 'yellow', 'tarefa_criada' => 'green', 'recusada' => 'red'];
@endphp
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary">Orientações técnicas às propriedades</div><h1 class="page-title">Minhas recomendações</h1></div><div class="col-auto"><a class="btn btn-primary" href="{{ route('agronomo.recomendacoes.create') }}">Nova recomendação</a></div></div></div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <form method="GET" class="card card-body mb-3"><div class="row g-2 align-items-end"><div class="col-md-4"><label for="status" class="form-label">Status</label><select id="status" name="status" class="form-select"><option value="">Todos</option>@foreach ($statusLabels as $key => $label)<option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>@endforeach</select></div><div class="col-auto"><button class="btn btn-primary">Filtrar</button> <a class="btn" href="{{ route('agronomo.recomendacoes.index') }}">Limpar</a></div></div></form>
    <div class="card"><div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Recomendação</th><th>Propriedade / talhão</th><th>Prioridade</th><th>Enviada em</th><th>Status</th><th></th></tr></thead><tbody>
        @forelse ($recomendacoes as $recomendacao)<tr><td class="fw-semibold">{{ $recomendacao->titulo }}</td><td>{{ $recomendacao->propriedade->nome }}<div class="text-secondary small">{{ $recomendacao->talhao?->nome ?? 'Toda a propriedade' }}</div></td><td>{{ ucfirst($recomendacao->prioridade) }}</td><td>{{ $recomendacao->created_at->format('d/m/Y') }}</td><td><span class="badge bg-{{ $statusColors[$recomendacao->status] }}-lt">{{ $statusLabels[$recomendacao->status] }}</span></td><td><a class="btn btn-sm" href="{{ route('agronomo.recomendacoes.show', $recomendacao) }}">Detalhes</a></td></tr>
        @empty<tr><td colspan="6" class="text-center text-secondary py-5">Nenhuma recomendação enviada. Registre uma orientação para iniciar o fluxo de análise.</td></tr>@endforelse
    </tbody></table></div><div class="card-footer">{{ $recomendacoes->links() }}</div></div>
</div></main>
@endsection
