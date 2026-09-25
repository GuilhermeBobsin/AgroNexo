@extends('layouts.admin.base')

@section('content')
@php $statusLabels = ['realizada' => 'Realizada', 'planejada' => 'Planejada', 'cancelada' => 'Cancelada']; $statusColors = ['realizada' => 'green', 'planejada' => 'yellow', 'cancelada' => 'red']; @endphp
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary mb-1"><a href="{{ route('agronomo.aplicacoes.index') }}">Aplicações</a> / Registro</div><h1 class="page-title">{{ $aplicacao->produto->nome }}</h1></div><div class="col-auto"><span class="badge bg-{{ $statusColors[$aplicacao->status] ?? 'secondary' }}-lt">{{ $statusLabels[$aplicacao->status] ?? $aplicacao->status }}</span></div></div></div>
    <div class="row"><div class="col-lg-9"><section class="card"><div class="card-header"><h2 class="card-title">Dados registrados</h2></div><div class="card-body"><div class="datagrid">
        <div class="datagrid-item"><div class="datagrid-title">Data e hora</div><div class="datagrid-content">{{ $aplicacao->data_aplicacao->format('d/m/Y') }} {{ $aplicacao->hora_aplicacao ? substr($aplicacao->hora_aplicacao, 0, 5) : '' }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Propriedade</div><div class="datagrid-content">{{ $aplicacao->talhao->propriedade->nome }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Talhão / cultura</div><div class="datagrid-content">{{ $aplicacao->talhao->nome }} · {{ $aplicacao->talhao->cultura?->nome ?? 'Sem cultura' }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Produto / quantidade</div><div class="datagrid-content">{{ $aplicacao->produto->nome }} · {{ number_format((float) $aplicacao->dose, 3, ',', '.') }} {{ $aplicacao->produto->unidade }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Operador</div><div class="datagrid-content">{{ $aplicacao->usuario->name }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Equipamento</div><div class="datagrid-content">{{ $aplicacao->equipamento ?: '—' }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Temperatura</div><div class="datagrid-content">{{ $aplicacao->temperatura !== null ? $aplicacao->temperatura.' °C' : '—' }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Umidade</div><div class="datagrid-content">{{ $aplicacao->umidade !== null ? $aplicacao->umidade.'%' : '—' }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Vento</div><div class="datagrid-content">{{ $aplicacao->velocidade_vento !== null ? $aplicacao->velocidade_vento.' km/h' : '—' }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Precipitação</div><div class="datagrid-content">{{ $aplicacao->precipitacao !== null ? $aplicacao->precipitacao.' mm' : '—' }}</div></div>
        <div class="datagrid-item"><div class="datagrid-title">Observações</div><div class="datagrid-content">{{ $aplicacao->observacoes ?: '—' }}</div></div>
    </div></div>
    @if ($aplicacao->tarefa)<div class="card-footer">Tarefa relacionada: <a href="{{ route('agronomo.tarefas.show', $aplicacao->tarefa) }}">{{ $aplicacao->tarefa->titulo }}</a></div>@endif
    <div class="card-footer"><a class="btn" href="{{ route('agronomo.aplicacoes.index') }}">Voltar às aplicações</a></div></section></div></div>
</div></main>
@endsection
