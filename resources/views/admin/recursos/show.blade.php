@extends('layouts.admin.base')

@section('content')
@php
    $tipos = ['trator' => 'Trator', 'implemento' => 'Implemento', 'pulverizador' => 'Pulverizador', 'colheitadeira' => 'Colheitadeira', 'outro' => 'Outro'];
    $statusLabels = ['disponivel' => 'Disponível', 'em_uso' => 'Em uso', 'manutencao' => 'Em manutenção'];
    $statusColors = ['disponivel' => 'green', 'em_uso' => 'blue', 'manutencao' => 'orange'];
@endphp
<main id="content" class="page-body">
    <div class="container-xl">
        <div class="page-header mb-4">
            <div class="row align-items-center"><div class="col">
                <div class="text-secondary mb-1"><a href="{{ route('admin.recursos.index') }}">Recursos</a> / Detalhes</div>
                <h2 class="page-title">{{ $recurso->nome }}</h2>
            </div><div class="col-auto ms-auto d-flex gap-2">
                <a href="{{ route('admin.recursos.index') }}" class="btn">Voltar</a>
                <a href="{{ route('admin.recursos.edit', $recurso) }}" class="btn btn-primary">Editar recurso</a>
            </div></div>
        </div>
        <div class="row row-cards"><div class="col-lg-8">
            <div class="card"><div class="card-header"><h3 class="card-title">Informações do recurso</h3></div>
                <div class="card-body"><div class="datagrid">
                    <div class="datagrid-item"><div class="datagrid-title">Nome</div><div class="datagrid-content fw-bold">{{ $recurso->nome }}</div></div>
                    <div class="datagrid-item"><div class="datagrid-title">Tipo</div><div class="datagrid-content">{{ $tipos[$recurso->tipo] ?? 'Outro' }}</div></div>
                    <div class="datagrid-item"><div class="datagrid-title">Status</div><div class="datagrid-content"><span class="badge bg-{{ $statusColors[$recurso->status] ?? 'secondary' }}-lt">{{ $statusLabels[$recurso->status] ?? $recurso->status }}</span></div></div>
                    <div class="datagrid-item"><div class="datagrid-title">Propriedade</div><div class="datagrid-content"><a href="{{ route('admin.propriedades.show', $recurso->propriedade) }}">{{ $recurso->propriedade->nome }}</a></div></div>
                    <div class="datagrid-item"><div class="datagrid-title">Cadastrado em</div><div class="datagrid-content">{{ $recurso->created_at?->format('d/m/Y') }}</div></div>
                </div></div>
            </div>
        </div></div>
    </div>
</main>
@endsection
