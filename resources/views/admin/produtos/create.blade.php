@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body">
    <div class="container-xl">

        {{-- Cabeçalho --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <div class="mb-2">
                        <a href="{{ route('admin.produtos.index') }}" class="text-secondary text-decoration-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1 me-1">
                                <path d="M15 6l-6 6l6 6"></path>
                            </svg>
                            Produtos
                        </a>
                    </div>
                    <h2 class="page-title">Novo produto</h2>
                    <div class="text-secondary mt-1">Cadastre um insumo para uso no estoque e nas tarefas de aplicação.</div>
                </div>
            </div>
        </div>

        <form id="form-produto" method="POST" action="{{ route('admin.produtos.store') }}" data-ajax-form>
            @csrf

            <div class="row g-4">

                {{-- Coluna esquerda: formulário --}}
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-header">
                            <span class="avatar avatar-sm bg-blue-lt me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                    <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                </svg>
                            </span>
                            <h3 class="card-title">Identificação</h3>
                        </div>
                        <div class="card-body">
                            @include('admin.produtos._form', ['produto' => null])
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('admin.produtos.index') }}" class="btn w-100">Cancelar</a>
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1 me-1">
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Cadastrar produto
                        </button>
                    </div>
                </div>

                {{-- Coluna direita: pré-visualização + orientações --}}
                <div class="col-lg-5">
                    <div class="text-secondary mb-2 small">Como vai aparecer</div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <span class="avatar bg-blue-lt me-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                    </svg>
                                </span>
                                <div>
                                    <div class="fw-bold" id="preview-nome">Nome do produto</div>
                                    <div class="text-secondary small" id="preview-principio">Princípio ativo não informado</div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="text-secondary small">Grupo / modo de ação</div>
                                    <div id="preview-grupo">
                                        <span class="badge bg-secondary-lt">Não informado</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-secondary small">Preço</div>
                                    <div class="fw-bold" id="preview-preco">
                                        — <span class="text-secondary fw-normal" id="preview-unidade">/ L</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <div class="text-secondary">
                            O <strong>grupo/modo de ação</strong> é o que permite ao sistema alertar quando o mesmo talhão recebe o mesmo grupo em aplicações seguidas. Vale preencher já na criação, mesmo que ainda não seja usado.
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary me-2 mt-1 flex-shrink-0">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                    <path d="M12 9h.01" />
                                    <path d="M11 12h1v4h1" />
                                </svg>
                                <div class="text-secondary small">
                                    Depois de criado, o produto ainda não tem estoque em nenhuma propriedade. O lançamento de estoque (quantidade, mínimo e validade) é feito separadamente, por propriedade.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
</main>

<script type="module">
    document.getElementById('form-produto')?.addEventListener('ajax-success', (event) => {
        event.detail.toastPromise.then(() => window.location.assign(event.detail.redirect));
    });

    document.addEventListener('DOMContentLoaded', () => {
        const inputNome = document.getElementById('nome');
        const inputPrincipio = document.getElementById('principio_ativo');
        const inputGrupo = document.getElementById('grupo_modo_acao');
        const inputUnidade = document.getElementById('unidade');
        const inputPreco = document.getElementById('preco');

        inputNome?.addEventListener('input', () => {
            document.getElementById('preview-nome').textContent = inputNome.value.trim() || 'Nome do produto';
        });

        inputPrincipio?.addEventListener('input', () => {
            document.getElementById('preview-principio').textContent = inputPrincipio.value.trim() || 'Princípio ativo não informado';
        });

        inputGrupo?.addEventListener('input', () => {
            const valor = inputGrupo.value.trim();
            document.getElementById('preview-grupo').innerHTML = valor
                ? `<span class="badge bg-purple-lt">${valor}</span>`
                : `<span class="badge bg-secondary-lt">Não informado</span>`;
        });

        function atualizarPreco() {
            const valor = parseFloat(inputPreco.value);
            const precoFormatado = !isNaN(valor)
                ? 'R$ ' + valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
                : '—';
            document.getElementById('preview-preco').firstChild.textContent = precoFormatado + ' ';
            document.getElementById('preview-unidade').textContent = '/ ' + (inputUnidade.value.trim() || 'un');
        }

        inputPreco?.addEventListener('input', atualizarPreco);
        inputUnidade?.addEventListener('input', atualizarPreco);
    });
</script>
@endsection