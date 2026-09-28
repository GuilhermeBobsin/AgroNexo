@extends('layouts.admin.base')

@section('content')
@php $statusLabels = ['pendente' => 'Aguardando análise', 'tarefa_criada' => 'Tarefa criada', 'recusada' => 'Recusada']; $statusColors = ['pendente' => 'yellow', 'tarefa_criada' => 'green', 'recusada' => 'red']; @endphp
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary mb-1"><a href="{{ route('admin.recomendacoes.index') }}">Recomendações</a> / Análise</div><h1 class="page-title">{{ $recomendacao->titulo }}</h1><div class="text-secondary">Enviada por {{ $recomendacao->agronomo->name }} em {{ $recomendacao->created_at->format('d/m/Y H:i') }}</div></div><div class="col-auto"><span class="badge bg-{{ $statusColors[$recomendacao->status] }}-lt">{{ $statusLabels[$recomendacao->status] }}</span></div></div></div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="row row-cards"><div class="col-lg-7"><div class="card"><div class="card-header"><h2 class="card-title">Recomendação técnica</h2></div><div class="card-body"><div class="datagrid">
        <div class="datagrid-item"><div class="datagrid-title">Propriedade / talhão</div><div class="datagrid-content">{{ $recomendacao->propriedade->nome }} · {{ $recomendacao->talhao?->nome ?? 'Toda a propriedade' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Tipo / prioridade</div><div class="datagrid-content">{{ ucfirst(str_replace('_', ' ', $recomendacao->tipo)) }} · {{ ucfirst($recomendacao->prioridade) }}</div></div><div class="datagrid-item"><div class="datagrid-title">Diagnóstico</div><div class="datagrid-content">{{ $recomendacao->diagnostico }}</div></div><div class="datagrid-item"><div class="datagrid-title">Orientação</div><div class="datagrid-content">{{ $recomendacao->orientacao }}</div></div>
        @if ($recomendacao->produto)<div class="datagrid-item"><div class="datagrid-title">Produto / dose sugerida</div><div class="datagrid-content">{{ $recomendacao->produto->nome }} · {{ number_format((float) $recomendacao->dose, 3, ',', '.') }} {{ $recomendacao->produto->unidade }}</div></div>@endif
    </div></div></div>
    @if ($recomendacao->parecer_admin)<div class="card mt-3"><div class="card-header"><h2 class="card-title">Parecer registrado</h2></div><div class="card-body">{{ $recomendacao->parecer_admin }}<div class="text-secondary small mt-2">{{ $recomendacao->analisadoPor?->name }} · {{ $recomendacao->analisado_em?->format('d/m/Y H:i') }}</div></div></div>@endif
    @if ($recomendacao->tarefa)<div class="alert alert-success mt-3">Tarefa vinculada: <a href="{{ route('admin.tarefas.show', $recomendacao->tarefa) }}">{{ $recomendacao->tarefa->titulo }}</a>, responsável {{ $recomendacao->tarefa->responsavel?->name }}.</div>@endif
    </div><div class="col-lg-5">
        @if ($recomendacao->status === 'pendente')
            <form method="POST" action="{{ route('admin.recomendacoes.criar-tarefa', $recomendacao) }}" class="card mb-3">@csrf @method('PATCH')<div class="card-header"><div><h2 class="card-title">Aprovar e criar tarefa</h2><div class="card-subtitle">A recomendação será encaminhada ao operador e ficará vinculada à tarefa.</div></div></div><div class="card-body">
                @if (!$dadosAplicacaoValidos)<div class="alert alert-warning">Os dados de talhão, produto ou dose desta aplicação foram removidos. Peça ao agrônomo para enviar uma nova recomendação.</div>@endif
                @if ($operadores->isEmpty())<div class="alert alert-warning">Nenhum operador ativo está vinculado a esta propriedade.</div>@endif
                <div class="mb-3"><label for="responsavel_id" class="form-label">Operador responsável</label><select id="responsavel_id" name="responsavel_id" class="form-select" required><option value="">Selecione</option>@foreach ($operadores as $operador)<option value="{{ $operador->id }}" @selected(old('responsavel_id') == $operador->id)>{{ $operador->name }}</option>@endforeach</select></div>
                <div class="mb-3"><label for="data_prevista" class="form-label">Data prevista</label><input id="data_prevista" name="data_prevista" type="date" class="form-control" value="{{ old('data_prevista', today()->addDay()->toDateString()) }}" required></div>
                <div class="mb-3"><label for="hora_prevista" class="form-label">Horário previsto</label><input id="hora_prevista" name="hora_prevista" type="time" class="form-control" value="{{ old('hora_prevista') }}"></div>
                @if (in_array($recomendacao->tipo, ['aplicacao', 'aracao', 'calagem'], true) && $recomendacao->talhao_id)
                    @include('components.previsao-tarefa', ['tipoClimaPadrao' => $recomendacao->tipo, 'talhaoClimaPadrao' => $recomendacao->talhao_id])
                @endif
                <div class="mb-3"><label for="recurso_id" class="form-label">Recurso (opcional)</label><select id="recurso_id" name="recurso_id" class="form-select"><option value="">Sem recurso associado</option>@foreach ($recursos as $recurso)<option value="{{ $recurso->id }}" @selected(old('recurso_id') == $recurso->id)>{{ $recurso->nome }}</option>@endforeach</select></div>
                <div><label for="parecer_admin" class="form-label">Parecer para o agrônomo (opcional)</label><textarea id="parecer_admin" name="parecer_admin" class="form-control" rows="3" maxlength="3000">{{ old('parecer_admin') }}</textarea></div>
            </div><div class="card-footer d-flex justify-content-end"><button class="btn btn-primary" @disabled($operadores->isEmpty() || !$dadosAplicacaoValidos)>Aprovar e encaminhar</button></div></form>
            <form method="POST" action="{{ route('admin.recomendacoes.recusar', $recomendacao) }}" class="card">@csrf @method('PATCH')<div class="card-header"><h2 class="card-title">Recusar recomendação</h2></div><div class="card-body"><label for="parecer_recusa" class="form-label">Explique o motivo da recusa</label><textarea id="parecer_recusa" name="parecer_admin" class="form-control" rows="3" minlength="5" maxlength="3000" required>{{ old('parecer_admin') }}</textarea></div><div class="card-footer d-flex justify-content-end"><button class="btn btn-outline-danger">Registrar recusa</button></div></form>
        @else<div class="card"><div class="card-header"><h2 class="card-title">Análise concluída</h2></div><div class="card-body">Esta recomendação já foi analisada e não pode ser processada novamente.</div></div>@endif
        <div class="mt-3"><a class="btn" href="{{ route('admin.recomendacoes.index') }}">Voltar à lista</a></div>
    </div></div>
</div></main>
@endsection
