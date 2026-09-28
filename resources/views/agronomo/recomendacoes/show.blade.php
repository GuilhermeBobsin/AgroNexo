@extends('layouts.admin.base')

@section('content')
@php $statusLabels = ['pendente' => 'Aguardando análise', 'tarefa_criada' => 'Tarefa criada', 'recusada' => 'Recusada']; $statusColors = ['pendente' => 'yellow', 'tarefa_criada' => 'green', 'recusada' => 'red']; @endphp
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary mb-1"><a href="{{ route('agronomo.recomendacoes.index') }}">Minhas recomendações</a> / Detalhes</div><h1 class="page-title">{{ $recomendacao->titulo }}</h1></div><div class="col-auto"><span class="badge bg-{{ $statusColors[$recomendacao->status] }}-lt">{{ $statusLabels[$recomendacao->status] }}</span></div></div></div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row row-cards"><div class="col-lg-8"><div class="card"><div class="card-header"><h2 class="card-title">Orientação técnica</h2></div><div class="card-body"><div class="datagrid">
        <div class="datagrid-item"><div class="datagrid-title">Propriedade</div><div class="datagrid-content">{{ $recomendacao->propriedade->nome }}</div></div><div class="datagrid-item"><div class="datagrid-title">Talhão</div><div class="datagrid-content">{{ $recomendacao->talhao?->nome ?? 'Toda a propriedade' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Tipo / prioridade</div><div class="datagrid-content">{{ ucfirst(str_replace('_', ' ', $recomendacao->tipo)) }} · {{ ucfirst($recomendacao->prioridade) }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Diagnóstico</div><div class="datagrid-content">{{ $recomendacao->diagnostico }}</div></div><div class="datagrid-item"><div class="datagrid-title">Orientação</div><div class="datagrid-content">{{ $recomendacao->orientacao }}</div></div>
        @if ($recomendacao->produto)<div class="datagrid-item"><div class="datagrid-title">Produto / dose sugerida</div><div class="datagrid-content">{{ $recomendacao->produto->nome }} · {{ number_format((float) $recomendacao->dose, 3, ',', '.') }} {{ $recomendacao->produto->unidade }}</div></div>@endif
        <div class="datagrid-item"><div class="datagrid-title">Enviada em</div><div class="datagrid-content">{{ $recomendacao->created_at->format('d/m/Y H:i') }}</div></div>
    </div></div></div>
    @if ($recomendacao->parecer_admin)<div class="card mt-3"><div class="card-header"><h2 class="card-title">Parecer do administrador</h2></div><div class="card-body">{{ $recomendacao->parecer_admin }}<div class="text-secondary small mt-2">{{ $recomendacao->analisadoPor?->name }} · {{ $recomendacao->analisado_em?->format('d/m/Y H:i') }}</div></div></div>@endif
    @if ($recomendacao->tarefa)<div class="alert alert-success mt-3">Tarefa criada para execução por {{ $recomendacao->tarefa->responsavel?->name }}. <a href="{{ route('agronomo.tarefas.show', $recomendacao->tarefa) }}">Acompanhar tarefa</a>.</div>@endif
    <div class="mt-3"><a class="btn" href="{{ route('agronomo.recomendacoes.index') }}">Voltar às recomendações</a></div>
    </div><div class="col-lg-4"><div class="card"><div class="card-header"><h2 class="card-title">Próxima etapa</h2></div><div class="card-body">@if ($recomendacao->status === 'pendente')O administrador analisará a recomendação. Se aprovada, ela será encaminhada como tarefa a um operador.@elseif ($recomendacao->status === 'tarefa_criada')A recomendação foi aprovada e convertida em tarefa.@else A recomendação foi recusada; consulte o parecer para ver o motivo.@endif</div></div></div></div>
</div></main>
@endsection
