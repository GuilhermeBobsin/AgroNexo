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
                    <h2 class="page-title">Editar produto</h2>
                    <div class="text-secondary mt-1">Atualize os dados de {{ $produto->nome }}.</div>
                </div>
                <div class="col-auto ms-auto">
                    <a href="{{ route('admin.produtos.show', $produto) }}" class="btn">Ver produto</a>
                </div>
            </div>
        </div>

        <form id="form-produto" method="POST" action="{{ route('admin.produtos.update', $produto) }}" data-ajax-form>
            @csrf
            @method('PUT')

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
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome do produto</label>
                                <input id="nome" name="nome" type="text" value="{{ old('nome', $produto?->nome) }}"
                                    class="form-control @error('nome') is-invalid @enderror" maxlength="255"
                                    placeholder="Ex: Fungicida agrícola" required autofocus>
                                <div class="invalid-feedback" id="error-nome">@error('nome'){{ $message }}@enderror</div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="principio_ativo" class="form-label">Princípio ativo</label>
                                    <input id="principio_ativo" name="principio_ativo" type="text"
                                        value="{{ old('principio_ativo', $produto?->principio_ativo) }}"
                                        class="form-control @error('principio_ativo') is-invalid @enderror" maxlength="255"
                                        placeholder="Ex: Azoxistrobina">
                                    <div class="invalid-feedback" id="error-principio_ativo">@error('principio_ativo'){{ $message }}@enderror</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="grupo_modo_acao" class="form-label">Grupo / modo de ação</label>
                                    <input id="grupo_modo_acao" name="grupo_modo_acao" type="text"
                                        value="{{ old('grupo_modo_acao', $produto?->grupo_modo_acao) }}"
                                        class="form-control @error('grupo_modo_acao') is-invalid @enderror" maxlength="255"
                                        placeholder="Ex: Grupo 11 (QoI)">
                                    <div class="invalid-feedback" id="error-grupo_modo_acao">@error('grupo_modo_acao'){{ $message }}@enderror</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <span class="avatar avatar-sm bg-green-lt me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M3 21l18 0" />
                                    <path d="M4 21v-13.343a1 1 0 0 1 .445 -.832l7 -4.666a1 1 0 0 1 1.11 0l7 4.666a1 1 0 0 1 .445 .832v13.343" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <h3 class="card-title">Estoque e preço</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="unidade" class="form-label">Unidade de estoque</label>
                                    <input id="unidade" name="unidade" type="text" value="{{ old('unidade', $produto?->unidade ?? 'L') }}"
                                        class="form-control @error('unidade') is-invalid @enderror" maxlength="255"
                                        placeholder="L, kg, un..." required>
                                    <div class="invalid-feedback" id="error-unidade">@error('unidade'){{ $message }}@enderror</div>
                                    <div class="form-hint">Ex.: L, kg, g ou unidade.</div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="preco" class="form-label">Preço unitário <span class="text-secondary">(opcional)</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input id="preco" name="preco" type="number" min="0" max="9999999999.99" step="0.01"
                                            value="{{ old('preco', $produto?->preco) }}"
                                            class="form-control @error('preco') is-invalid @enderror">
                                    </div>
                                    <div class="invalid-feedback d-block" id="error-preco">@error('preco'){{ $message }}@enderror</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <a href="{{ route('admin.produtos.show', $produto) }}" class="btn w-100">Cancelar</a>
                        <button type="submit" class="btn btn-primary w-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1 me-1">
                                <path d="M5 12l5 5l10 -10"></path>
                            </svg>
                            Salvar alterações
                        </button>
                    </div>
                </div>

                {{-- Coluna direita: pré-visualização + contexto de uso --}}
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
                                    <div class="fw-bold" id="preview-nome">{{ $produto->nome }}</div>
                                    <div class="text-secondary small" id="preview-principio">{{ $produto->principio_ativo ?: 'Princípio ativo não informado' }}</div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="text-secondary small">Grupo / modo de ação</div>
                                    <div id="preview-grupo">
                                        @if ($produto->grupo_modo_acao)
                                            <span class="badge bg-purple-lt">{{ $produto->grupo_modo_acao }}</span>
                                        @else
                                            <span class="badge bg-secondary-lt">Não informado</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-secondary small">Preço</div>
                                    <div class="fw-bold" id="preview-preco">
                                        {{ $produto->preco !== null ? 'R$ '.number_format((float) $produto->preco, 2, ',', '.') : '—' }}
                                        <span class="text-secondary fw-normal" id="preview-unidade">/ {{ $produto->unidade }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <div class="text-secondary">
                            O <strong>grupo/modo de ação</strong> é o que permite ao sistema alertar quando o mesmo talhão recebe o mesmo grupo em aplicações seguidas — deixar em branco desativa esse alerta pra este produto.
                        </div>
                    </div>

                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="text-secondary mb-2">Uso atual deste produto</div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-{{ $produto->propriedades()->count() ? 'green' : 'secondary' }}-lt">
                                    {{ $produto->propriedades()->count() }}
                                </span>
                                <span class="text-secondary small">
                                    {{ $produto->propriedades()->count() === 1 ? 'propriedade tem' : 'propriedades têm' }} estoque deste produto
                                </span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $produto->aplicacoes()->count() ? 'blue' : 'secondary' }}-lt">
                                    {{ $produto->aplicacoes()->count() }}
                                </span>
                                <span class="text-secondary small">
                                    {{ $produto->aplicacoes()->count() === 1 ? 'aplicação registrada' : 'aplicações registradas' }}
                                </span>
                            </div>
                            @if ($produto->aplicacoes()->count() > 0)
                                <div class="text-secondary small mt-2">
                                    Mudar nome ou unidade não afeta o histórico já registrado.
                                </div>
                            @endif
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