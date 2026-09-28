@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body">
    <div class="container-xl">

        {{-- Cabeçalho --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Produtos</h2>
                    <div class="text-secondary mt-1">Gerencie os insumos cadastrados no AgroNexo.</div>
                </div>
                <div class="col-auto ms-auto">
                    <a href="{{ route('admin.produtos.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                        </svg>
                        Novo produto
                    </a>
                </div>
            </div>
        </div>

        {{-- Mensagem de sucesso --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">
                <div class="alert-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon">
                        <path d="M5 12l5 5l10 -10"></path>
                    </svg>
                </div>
                <div>{{ session('success') }}</div>
                <a href="#" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></a>
            </div>
        @endif

        {{-- Resumo --}}
        <div class="row row-deck row-cards mb-4">

            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-blue-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Produtos cadastrados</div>
                                <div class="h2 mb-0">{{ $totalProdutos }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-green-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                    <path d="M12 12l8 -4.5" />
                                    <path d="M12 12l0 9" />
                                    <path d="M12 12l-8 -4.5" />
                                    <path d="M16 5.25l-8 4.5" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Com estoque em propriedades</div>
                                <div class="h2 mb-0">{{ $comEstoque }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-orange-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 9v4" />
                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                    <path d="M12 16h.01" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Sem estoque em nenhuma propriedade</div>
                                <div class="h2 mb-0">{{ max(0, $totalProdutos - $comEstoque) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Lista --}}
        <div class="card">

            {{-- Busca --}}
            <div class="card-body border-bottom py-3">
                <form method="GET" class="row g-2 align-items-center">
                    <div class="col-md-6 col-lg-5">
                        <label for="busca" class="visually-hidden">Buscar produto</label>
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" />
                                    <path d="M21 21l-6 -6" />
                                </svg>
                            </span>
                            <input id="busca" name="busca" value="{{ request('busca') }}" class="form-control"
                                placeholder="Buscar por nome, princípio ativo ou grupo" autocomplete="off">
                        </div>
                    </div>

                    <div class="col-auto">
                        <button class="btn btn-primary">Buscar</button>
                        @if (request('busca'))
                            <a href="{{ route('admin.produtos.index') }}" class="btn">Limpar</a>
                        @endif
                    </div>

                    <div class="col-auto ms-md-auto text-secondary small">
                        {{ $produtos->total() }} {{ $produtos->total() === 1 ? 'produto' : 'produtos' }}
                    </div>
                </form>
            </div>

            {{-- Tabela --}}
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Princípio ativo</th>
                            <th>Grupo de modo de ação</th>
                            <th>Preço</th>
                            <th>Estoque em</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produtos as $produto)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm bg-blue-lt me-3">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                            </svg>
                                        </span>
                                        <div>
                                            <a href="{{ route('admin.produtos.show', $produto) }}" class="fw-bold text-reset">{{ $produto->nome }}</a>
                                            @if (($produto->aplicacoes_count + $produto->tarefas_count) > 0)
                                                <span class="badge bg-blue-lt ms-1">Em uso</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td>{{ $produto->principio_ativo ?: '—' }}</td>

                                <td>
                                    @if ($produto->grupo_modo_acao)
                                        <span class="badge bg-purple-lt">{{ $produto->grupo_modo_acao }}</span>
                                    @else
                                        <span class="badge bg-secondary-lt" title="Informe o grupo para o produto entrar na verificação de rotação">Não informado</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="fw-bold">
                                        {{ $produto->preco !== null ? 'R$ '.number_format((float) $produto->preco, 2, ',', '.') : '—' }}
                                    </div>
                                    <div class="text-secondary small">por {{ $produto->unidade }}</div>
                                </td>

                                <td>
                                    <span class="badge bg-{{ $produto->propriedades_count ? 'green' : 'secondary' }}-lt">
                                        {{ $produto->propriedades_count }}
                                        {{ $produto->propriedades_count === 1 ? 'propriedade' : 'propriedades' }}
                                    </span>
                                </td>

                                <td>
                                    <div class="btn-list flex-nowrap justify-content-end">
                                        <a href="{{ route('admin.produtos.show', $produto) }}" class="btn btn-icon btn-sm" title="Ver" aria-label="Ver {{ $produto->nome }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                            </svg>
                                        </a>

                                        <a href="{{ route('admin.produtos.edit', $produto) }}" class="btn btn-icon btn-sm" title="Editar" aria-label="Editar {{ $produto->nome }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                <path d="M16 5l3 3" />
                                            </svg>
                                        </a>

                                        <button type="button" class="btn btn-icon btn-sm text-danger btn-remover-produto"
                                            title="Remover" aria-label="Remover {{ $produto->nome }}"
                                            data-url="{{ route('admin.produtos.destroy', $produto) }}"
                                            data-nome="{{ $produto->nome }}"
                                            data-estoques="{{ $produto->propriedades_count }}"
                                            data-usos="{{ $produto->aplicacoes_count + $produto->tarefas_count }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M4 7l16 0" />
                                                <path d="M10 11l0 6" />
                                                <path d="M14 11l0 6" />
                                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty">
                                        <p class="empty-title">
                                            @if (request('busca'))
                                                Nenhum resultado para “{{ request('busca') }}”
                                            @else
                                                Nenhum produto cadastrado
                                            @endif
                                        </p>
                                        <p class="empty-subtitle text-secondary">
                                            @if (request('busca'))
                                                Tente outro termo ou limpe a busca para ver todos os produtos.
                                            @else
                                                Cadastre o primeiro insumo para usar nas tarefas e no estoque.
                                            @endif
                                        </p>
                                        <div class="empty-action">
                                            @if (request('busca'))
                                                <a href="{{ route('admin.produtos.index') }}" class="btn">Limpar busca</a>
                                            @else
                                                <a href="{{ route('admin.produtos.create') }}" class="btn btn-primary">Novo produto</a>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($produtos->total() > 0)
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-secondary">
                        Mostrando <strong>{{ $produtos->firstItem() }}</strong> a <strong>{{ $produtos->lastItem() }}</strong>
                        de <strong>{{ $produtos->total() }}</strong>
                    </p>
                    <div class="ms-auto">{{ $produtos->withQueryString()->links() }}</div>
                </div>
            @endif

        </div>
    </div>
</main>

<script type="module">
    document.querySelectorAll('.btn-remover-produto').forEach((button) => button.addEventListener('click', async () => {
        const alertText = Number(button.dataset.usos) > 0
            ? 'Este produto está associado a tarefas ou aplicações e não poderá ser removido.'
            : `${button.dataset.estoques} registro(s) de estoque associado(s) também serão removidos.`;

        const result = await Swal.fire({
            icon: 'warning',
            title: `Remover ${button.dataset.nome}?`,
            text: alertText,
            showCancelButton: true,
            confirmButtonText: 'Remover produto',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#d63939',
        });

        if (!result.isConfirmed) return;

        try {
            const response = await fetch(button.dataset.url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content, Accept: 'application/json' },
                body: new URLSearchParams({ _method: 'DELETE' }),
            });
            const data = await response.json();

            if (!response.ok) {
                await Swal.fire({ icon: 'error', title: 'Não foi possível remover', text: data.message || 'Tente novamente.' });
                return;
            }

            await Swal.fire({ icon: 'success', title: 'Produto removido', text: data.message, timer: 1500, showConfirmButton: false });
            window.location.reload();
        } catch (error) {
            await Swal.fire({ icon: 'error', title: 'Erro de conexão', text: 'Não foi possível se conectar ao servidor.' });
        }
    }));
</script>
@endsection