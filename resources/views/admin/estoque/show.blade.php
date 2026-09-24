@extends('layouts.admin.base')

@section('content')
@php
    $fmt = fn ($valor) => rtrim(rtrim(number_format((float) $valor, 3, ',', '.'), '0'), ',');
    $baixo = (float) $estoque->estoque_atual <= (float) $estoque->estoque_minimo;
    $vencido = $estoque->data_validade && $estoque->data_validade < today()->toDateString();
@endphp
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary mb-1"><a href="{{ route('admin.estoque.index') }}">Estoque</a> / Detalhes</div><h2 class="page-title">{{ $estoque->produto_nome }}</h2><div class="text-secondary mt-1">{{ $estoque->propriedade_nome }}</div></div><div class="col-auto ms-auto d-flex gap-2"><a href="{{ route('admin.estoque.index') }}" class="btn">Voltar</a><a href="{{ route('admin.estoque.edit', $estoque->id) }}" class="btn btn-primary">Editar estoque</a></div></div></div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row row-cards"><div class="col-lg-8"><div class="card"><div class="card-header"><h3 class="card-title">Informações do estoque</h3><div class="card-actions">@if ($vencido)<span class="badge bg-red-lt">Vencido</span>@elseif ($baixo)<span class="badge bg-orange-lt">Abaixo do mínimo</span>@else<span class="badge bg-green-lt">Normal</span>@endif</div></div><div class="card-body"><div class="datagrid">
        <div class="datagrid-item"><div class="datagrid-title">Produto</div><div class="datagrid-content fw-bold">{{ $estoque->produto_nome }}</div></div><div class="datagrid-item"><div class="datagrid-title">Propriedade</div><div class="datagrid-content">{{ $estoque->propriedade_nome }}</div></div><div class="datagrid-item"><div class="datagrid-title">Princípio ativo</div><div class="datagrid-content">{{ $estoque->principio_ativo ?: '—' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Grupo / modo de ação</div><div class="datagrid-content">{{ $estoque->grupo_modo_acao ?: '—' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Quantidade atual</div><div class="datagrid-content">{{ $fmt($estoque->estoque_atual) }} {{ $estoque->unidade }}</div></div><div class="datagrid-item"><div class="datagrid-title">Estoque mínimo</div><div class="datagrid-content">{{ $fmt($estoque->estoque_minimo) }} {{ $estoque->unidade }}</div></div><div class="datagrid-item"><div class="datagrid-title">Data de validade</div><div class="datagrid-content">{{ $estoque->data_validade ? \Illuminate\Support\Carbon::parse($estoque->data_validade)->format('d/m/Y') : 'Não informada' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Preço cadastrado</div><div class="datagrid-content">{{ $estoque->preco !== null ? 'R$ '.number_format((float) $estoque->preco, 2, ',', '.') : '—' }}</div></div>
    </div></div></div></div></div>
</div></main>
@endsection
