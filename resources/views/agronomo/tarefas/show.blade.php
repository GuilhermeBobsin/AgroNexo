@extends('layouts.admin.base')

@section('content')
@php
    $statusLabels = ['pendente' => 'Pendente', 'em_andamento' => 'Em andamento', 'concluida' => 'Concluída', 'cancelada' => 'Cancelada'];
    $statusColors = ['pendente' => 'yellow', 'em_andamento' => 'blue', 'concluida' => 'green', 'cancelada' => 'red'];
@endphp
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary mb-1"><a href="{{ route('agronomo.tarefas.index') }}">Tarefas</a> / Detalhes</div><h1 class="page-title">{{ $tarefa->titulo }}</h1></div><div class="col-auto"><span class="badge bg-{{ $statusColors[$tarefa->status] ?? 'secondary' }}-lt">{{ $statusLabels[$tarefa->status] ?? $tarefa->status }}</span></div></div></div>
    @if ($tarefa->aplicacao)<div class="alert alert-success">Esta tarefa gerou um registro de aplicação. <a href="{{ route('agronomo.aplicacoes.show', $tarefa->aplicacao) }}">Ver aplicação</a>.</div>@endif
    <div class="row"><div class="col-lg-9"><section class="card"><div class="card-header"><h2 class="card-title">Dados da tarefa</h2></div><div class="card-body"><div class="datagrid">
        <div class="datagrid-item"><div class="datagrid-title">Tipo</div><div class="datagrid-content">{{ ucfirst(str_replace('_', ' ', $tarefa->tipo)) }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Propriedade</div><div class="datagrid-content">{{ $tarefa->propriedade->nome }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Talhão / cultura</div><div class="datagrid-content">{{ $tarefa->talhao->nome ?? '—' }}{{ $tarefa->talhao?->cultura ? ' · '.$tarefa->talhao->cultura->nome : '' }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Responsável</div><div class="datagrid-content">{{ $tarefa->responsavel->name }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Recurso</div><div class="datagrid-content">{{ $tarefa->recurso->nome ?? '—' }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Data prevista</div><div class="datagrid-content">{{ $tarefa->data_prevista->format('d/m/Y') }} {{ $tarefa->hora_prevista ? substr($tarefa->hora_prevista, 0, 5) : '' }}</div></div>
        @if ($tarefa->produto)<div class="datagrid-item"><div class="datagrid-title">Produto / dose planejada</div><div class="datagrid-content">{{ $tarefa->produto->nome }} · {{ number_format((float) $tarefa->dose, 3, ',', '.') }} {{ $tarefa->produto->unidade }}</div></div>@endif
        @if ($tarefa->data_conclusao)<div class="datagrid-item"><div class="datagrid-title">Concluída em</div><div class="datagrid-content">{{ $tarefa->data_conclusao->format('d/m/Y H:i') }}</div></div>@endif
        <div class="datagrid-item"><div class="datagrid-title">Orientações</div><div class="datagrid-content">{{ $tarefa->observacoes ?: '—' }}</div></div>
    </div></div><div class="card-footer"><a class="btn" href="{{ route('agronomo.tarefas.index') }}">Voltar às tarefas</a></div></section></div></div>
</div></main>
@endsection
