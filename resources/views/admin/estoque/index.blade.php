@extends('layouts.admin.base')

@section('content')
@php
    $fmt = fn ($valor) => rtrim(rtrim(number_format((float) $valor, 3, ',', '.'), '0'), ',');
@endphp
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header d-print-none mb-4"><div class="row align-items-center"><div class="col"><h2 class="page-title">Estoque</h2><div class="text-secondary mt-1">Acompanhe os produtos, quantidades e validades por propriedade.</div></div><div class="col-auto ms-auto"><a href="{{ route('admin.estoque.create') }}" class="btn btn-primary">+ Novo estoque</a></div></div></div>
    @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="row row-cards mb-4">
        @foreach ([['Produtos em estoque', $resumo->total ?? 0, 'blue'], ['Abaixo do mínimo', $resumo->baixos ?? 0, 'orange'], ['Validade vencida', $resumo->vencidos ?? 0, 'red']] as [$titulo, $valor, $cor])<div class="col-sm-6 col-lg-3"><div class="card"><div class="card-body"><div class="d-flex align-items-center"><span class="avatar bg-{{ $cor }}-lt me-3">{{ $valor }}</span><div><div class="text-secondary">{{ $titulo }}</div><div class="h2 mb-0">{{ $valor }}</div></div></div></div></div></div>@endforeach
    </div>
    <form method="GET" class="card card-body mb-3"><div class="row g-2 align-items-end">
        <div class="col-md-5"><label class="form-label" for="busca">Buscar produto ou propriedade</label><input id="busca" name="busca" value="{{ request('busca') }}" class="form-control" placeholder="Nome ou princípio ativo"></div>
        <div class="col-md-4"><label class="form-label" for="filtro-propriedade">Propriedade</label><select id="filtro-propriedade" name="propriedade_id" class="form-select"><option value="">Todas as propriedades</option>@foreach ($propriedades as $propriedade)<option value="{{ $propriedade->id }}" @selected(request('propriedade_id') == $propriedade->id)>{{ $propriedade->nome }}</option>@endforeach</select></div>
        <div class="col-auto"><button class="btn btn-primary">Filtrar</button> <a href="{{ route('admin.estoque.index') }}" class="btn">Limpar</a></div>
    </div></form>
    <div class="card"><div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Produto</th><th>Propriedade</th><th>Quantidade</th><th>Mínimo</th><th>Validade</th><th>Situação</th><th></th></tr></thead><tbody>
        @forelse ($estoques as $estoque)
            @php $baixo = (float) $estoque->estoque_atual <= (float) $estoque->estoque_minimo; $vencido = $estoque->data_validade && $estoque->data_validade < today()->toDateString(); @endphp
            <tr><td><a href="{{ route('admin.estoque.show', $estoque->id) }}" class="fw-bold">{{ $estoque->produto_nome }}</a><div class="text-secondary small">{{ $estoque->principio_ativo ?: 'Sem princípio ativo informado' }}</div></td><td>{{ $estoque->propriedade_nome }}</td><td>{{ $fmt($estoque->estoque_atual) }} {{ $estoque->unidade }}</td><td>{{ $fmt($estoque->estoque_minimo) }} {{ $estoque->unidade }}</td><td>{{ $estoque->data_validade ? \Illuminate\Support\Carbon::parse($estoque->data_validade)->format('d/m/Y') : '—' }}</td><td>@if ($vencido)<span class="badge bg-red-lt">Vencido</span>@elseif ($baixo)<span class="badge bg-orange-lt">Baixo</span>@else<span class="badge bg-green-lt">Normal</span>@endif</td><td><div class="btn-list flex-nowrap"><a href="{{ route('admin.estoque.show', $estoque->id) }}" class="btn btn-sm">Ver</a><a href="{{ route('admin.estoque.edit', $estoque->id) }}" class="btn btn-sm">Editar</a><button type="button" class="btn btn-sm btn-outline-danger btn-remover-estoque" data-url="{{ route('admin.estoque.destroy', $estoque->id) }}" data-produto="{{ $estoque->produto_nome }}" data-propriedade="{{ $estoque->propriedade_nome }}">Remover</button></div></td></tr>
        @empty<tr><td colspan="7" class="text-center text-secondary py-5">Nenhum registro de estoque encontrado. <a href="{{ route('admin.estoque.create') }}">Cadastrar estoque</a></td></tr>@endforelse
    </tbody></table></div></div>
    <div class="mt-3">{{ $estoques->links() }}</div>
</div></main>
<script type="module">
    document.querySelectorAll('.btn-remover-estoque').forEach((button) => button.addEventListener('click', async () => {
        const result = await Swal.fire({ icon: 'warning', title: 'Remover este estoque?', text: `${button.dataset.produto} · ${button.dataset.propriedade}`, showCancelButton: true, confirmButtonText: 'Remover', cancelButtonText: 'Cancelar', confirmButtonColor: '#d63939' });
        if (!result.isConfirmed) return;
        try {
            const response = await fetch(button.dataset.url, { method: 'POST', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, Accept: 'application/json' }, body: new URLSearchParams({ _method: 'DELETE' }) });
            const data = await response.json();
            if (!response.ok) { await Swal.fire({ icon: 'error', title: 'Não foi possível remover', text: data.message || 'Tente novamente.' }); return; }
            await Swal.fire({ icon: 'success', title: 'Removido', text: data.message, timer: 1500, showConfirmButton: false });
            window.location.reload();
        } catch (error) { await Swal.fire({ icon: 'error', title: 'Erro de conexão', text: 'Não foi possível se conectar ao servidor.' }); }
    }));
</script>
@endsection
