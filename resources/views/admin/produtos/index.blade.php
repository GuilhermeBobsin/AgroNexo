@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header d-print-none mb-4"><div class="row align-items-center"><div class="col"><h2 class="page-title">Produtos</h2><div class="text-secondary mt-1">Gerencie os insumos cadastrados no AgroNexo.</div></div><div class="col-auto ms-auto"><a href="{{ route('admin.produtos.create') }}" class="btn btn-primary">+ Novo produto</a></div></div></div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row row-cards mb-4">
        <div class="col-sm-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-secondary">Produtos cadastrados</div><div class="h2 mb-0">{{ $totalProdutos }}</div></div></div></div>
        <div class="col-sm-6 col-lg-3"><div class="card"><div class="card-body"><div class="text-secondary">Com estoque em propriedades</div><div class="h2 mb-0">{{ $comEstoque }}</div></div></div></div>
    </div>
    <form method="GET" class="card card-body mb-3"><div class="row g-2"><div class="col-md-6"><label for="busca" class="form-label">Buscar produto</label><input id="busca" name="busca" value="{{ request('busca') }}" class="form-control" placeholder="Nome, princípio ativo ou grupo"></div><div class="col-auto align-self-end"><button class="btn btn-primary">Buscar</button> <a href="{{ route('admin.produtos.index') }}" class="btn">Limpar</a></div></div></form>
    <div class="card"><div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Produto</th><th>Princípio ativo</th><th>Unidade</th><th>Preço</th><th>Propriedades</th><th></th></tr></thead><tbody>
        @forelse ($produtos as $produto)<tr><td><a href="{{ route('admin.produtos.show', $produto) }}" class="fw-bold">{{ $produto->nome }}</a><div class="text-secondary small">{{ $produto->grupo_modo_acao ?: 'Grupo não informado' }}</div></td><td>{{ $produto->principio_ativo ?: '—' }}</td><td>{{ $produto->unidade }}</td><td>{{ $produto->preco !== null ? 'R$ '.number_format((float) $produto->preco, 2, ',', '.') : '—' }}</td><td><span class="badge bg-{{ $produto->propriedades_count ? 'green' : 'secondary' }}-lt">{{ $produto->propriedades_count }}</span></td><td><div class="btn-list flex-nowrap"><a href="{{ route('admin.produtos.show', $produto) }}" class="btn btn-sm">Ver</a><a href="{{ route('admin.produtos.edit', $produto) }}" class="btn btn-sm">Editar</a><button type="button" class="btn btn-sm btn-outline-danger btn-remover-produto" data-url="{{ route('admin.produtos.destroy', $produto) }}" data-nome="{{ $produto->nome }}" data-estoques="{{ $produto->propriedades_count }}" data-usos="{{ $produto->aplicacoes_count + $produto->tarefas_count }}">Remover</button></div></td></tr>
        @empty<tr><td colspan="6" class="text-center text-secondary py-5">Nenhum produto encontrado. <a href="{{ route('admin.produtos.create') }}">Cadastre o primeiro produto.</a></td></tr>@endforelse
    </tbody></table></div></div>
    <div class="mt-3">{{ $produtos->links() }}</div>
</div></main>
<script type="module">
    document.querySelectorAll('.btn-remover-produto').forEach((button) => button.addEventListener('click', async () => {
        const alertText = Number(button.dataset.usos) > 0
            ? 'Este produto está associado a tarefas ou aplicações e não poderá ser removido.'
            : `${button.dataset.estoques} registro(s) de estoque associado(s) também serão removidos.`;
        const result = await Swal.fire({ icon: 'warning', title: `Remover ${button.dataset.nome}?`, text: alertText, showCancelButton: true, confirmButtonText: 'Remover produto', cancelButtonText: 'Cancelar', confirmButtonColor: '#d63939' });
        if (!result.isConfirmed) return;
        try {
            const response = await fetch(button.dataset.url, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, Accept: 'application/json' }, body: new URLSearchParams({ _method: 'DELETE' }) });
            const data = await response.json();
            if (!response.ok) { await Swal.fire({ icon: 'error', title: 'Não foi possível remover', text: data.message || 'Tente novamente.' }); return; }
            await Swal.fire({ icon: 'success', title: 'Produto removido', text: data.message, timer: 1500, showConfirmButton: false });
            window.location.reload();
        } catch (error) { await Swal.fire({ icon: 'error', title: 'Erro de conexão', text: 'Não foi possível se conectar ao servidor.' }); }
    }));
</script>
@endsection
