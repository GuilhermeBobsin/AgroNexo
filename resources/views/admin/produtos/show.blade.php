@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary mb-1"><a href="{{ route('admin.produtos.index') }}">Produtos</a> / Detalhes</div><h2 class="page-title">{{ $produto->nome }}</h2></div><div class="col-auto ms-auto d-flex gap-2"><a href="{{ route('admin.produtos.index') }}" class="btn">Voltar</a><a href="{{ route('admin.produtos.edit', $produto) }}" class="btn btn-primary">Editar produto</a></div></div></div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row row-cards"><div class="col-lg-8"><div class="card"><div class="card-header"><h3 class="card-title">Informações do produto</h3></div><div class="card-body"><div class="datagrid">
        <div class="datagrid-item"><div class="datagrid-title">Nome</div><div class="datagrid-content fw-bold">{{ $produto->nome }}</div></div><div class="datagrid-item"><div class="datagrid-title">Princípio ativo</div><div class="datagrid-content">{{ $produto->principio_ativo ?: '—' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Grupo / modo de ação</div><div class="datagrid-content">{{ $produto->grupo_modo_acao ?: '—' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Unidade</div><div class="datagrid-content">{{ $produto->unidade }}</div></div><div class="datagrid-item"><div class="datagrid-title">Preço unitário</div><div class="datagrid-content">{{ $produto->preco !== null ? 'R$ '.number_format((float) $produto->preco, 2, ',', '.') : 'Não informado' }}</div></div><div class="datagrid-item"><div class="datagrid-title">Aplicações registradas</div><div class="datagrid-content">{{ $produto->aplicacoes_count }}</div></div><div class="datagrid-item"><div class="datagrid-title">Tarefas de aplicação</div><div class="datagrid-content">{{ $produto->tarefas_count }}</div></div>
    </div></div></div>
    <div class="card mt-3"><div class="card-header"><h3 class="card-title">Estoque por propriedade</h3></div><div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Propriedade</th><th>Quantidade</th><th>Mínimo</th><th>Validade</th></tr></thead><tbody>
        @forelse ($estoques as $propriedade)<tr><td>{{ $propriedade->nome }}</td><td>{{ number_format((float) $propriedade->pivot->estoque_atual, 3, ',', '.') }} {{ $produto->unidade }}</td><td>{{ number_format((float) $propriedade->pivot->estoque_minimo, 3, ',', '.') }} {{ $produto->unidade }}</td><td>{{ $propriedade->pivot->data_validade ? \Illuminate\Support\Carbon::parse($propriedade->pivot->data_validade)->format('d/m/Y') : '—' }}</td></tr>
        @empty<tr><td colspan="4" class="text-center text-secondary py-4">Este produto ainda não foi lançado no estoque.</td></tr>@endforelse
    </tbody></table></div><div class="card-footer"><a href="{{ route('admin.estoque.create') }}" class="btn btn-sm">Lançar estoque</a></div></div>
    </div></div>
</div></main>
@endsection
