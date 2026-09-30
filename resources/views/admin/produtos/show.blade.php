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

                    <div class="d-flex align-items-center">
                        <span class="avatar bg-blue-lt me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="page-title mb-0">{{ $produto->nome }}</h2>
                            <div class="text-secondary">
                                {{ $produto->principio_ativo ?: 'Princípio ativo não informado' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-auto ms-auto">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.produtos.index') }}" class="btn">Voltar</a>
                        <a href="{{ route('admin.produtos.edit', $produto) }}" class="btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                <path d="M16 5l3 3" />
                            </svg>
                            Editar produto
                        </a>
                    </div>
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

        @php
            $estoqueTotal = $estoques->sum('pivot.estoque_atual');
            $estoqueBaixoCount = $estoques->filter(fn ($p) => (float) $p->pivot->estoque_minimo > 0 && (float) $p->pivot->estoque_atual <= (float) $p->pivot->estoque_minimo)->count();
            $usosCount = $produto->aplicacoes_count + $produto->tarefas_count;
        @endphp

        {{-- Resumo --}}
        <div class="row row-deck row-cards mb-4">

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-green-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M3 21l18 0" />
                                    <path d="M4 21v-13.343a1 1 0 0 1 .445 -.832l7 -4.666a1 1 0 0 1 1.11 0l7 4.666a1 1 0 0 1 .445 .832v13.343" />
                                    <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Estoque total</div>
                                <div class="h2 mb-0">{{ number_format((float) $estoqueTotal, 1, ',', '.') }} {{ $produto->unidade }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-purple-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M3 21l18 0"></path>
                                    <path d="M5 21v-14l8 -4l6 3v15"></path>
                                    <path d="M9 21v-8h4v8"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Propriedades com estoque</div>
                                <div class="h2 mb-0">{{ $estoques->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-{{ $estoqueBaixoCount > 0 ? 'red' : 'secondary' }}-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 9v4" />
                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                    <path d="M12 16h.01" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Estoque baixo</div>
                                <div class="h2 mb-0">{{ $estoqueBaixoCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-blue-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Aplicações + tarefas</div>
                                <div class="h2 mb-0">{{ $usosCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4">

            {{-- Coluna esquerda --}}
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informações do produto</h3>
                    </div>
                    <div class="card-body">
                        <div class="datagrid">
                            <div class="datagrid-item">
                                <div class="datagrid-title">Nome</div>
                                <div class="datagrid-content fw-bold">{{ $produto->nome }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Princípio ativo</div>
                                <div class="datagrid-content">{{ $produto->principio_ativo ?: '—' }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Grupo / modo de ação</div>
                                <div class="datagrid-content">
                                    @if ($produto->grupo_modo_acao)
                                        <span class="badge bg-purple-lt">{{ $produto->grupo_modo_acao }}</span>
                                    @else
                                        <span class="badge bg-secondary-lt">Não informado</span>
                                    @endif
                                </div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Unidade</div>
                                <div class="datagrid-content">{{ $produto->unidade }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Preço unitário</div>
                                <div class="datagrid-content">{{ $produto->preco !== null ? 'R$ '.number_format((float) $produto->preco, 2, ',', '.') : 'Não informado' }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Aplicações registradas</div>
                                <div class="datagrid-content">{{ $produto->aplicacoes_count }}</div>
                            </div>
                            <div class="datagrid-item">
                                <div class="datagrid-title">Tarefas de aplicação</div>
                                <div class="datagrid-content">{{ $produto->tarefas_count }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Estoque por propriedade</h3>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Propriedade</th>
                                    <th>Quantidade</th>
                                    <th>Mínimo</th>
                                    <th>Validade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($estoques as $propriedade)
                                    @php
                                        $baixo = (float) $propriedade->pivot->estoque_minimo > 0
                                            && (float) $propriedade->pivot->estoque_atual <= (float) $propriedade->pivot->estoque_minimo;
                                        $validade = $propriedade->pivot->data_validade
                                            ? \Illuminate\Support\Carbon::parse($propriedade->pivot->data_validade)
                                            : null;
                                    @endphp
                                    <tr>
                                        <td class="fw-semibold">{{ $propriedade->nome }}</td>
                                        <td>
                                            <span class="{{ $baixo ? 'text-danger fw-bold' : '' }}">
                                                {{ number_format((float) $propriedade->pivot->estoque_atual, 3, ',', '.') }} {{ $produto->unidade }}
                                            </span>
                                            @if ($baixo)
                                                <span class="badge bg-red-lt ms-1">Baixo</span>
                                            @endif
                                        </td>
                                        <td class="text-secondary">
                                            {{ number_format((float) $propriedade->pivot->estoque_minimo, 3, ',', '.') }} {{ $produto->unidade }}
                                        </td>
                                        <td>
                                            @if (!$validade)
                                                <span class="text-secondary">—</span>
                                            @elseif ($validade->isPast())
                                                <span class="badge bg-red-lt">Vencido em {{ $validade->format('d/m/Y') }}</span>
                                            @elseif ($validade->diffInDays(now()) <= 30)
                                                <span class="badge bg-orange-lt">Vence em {{ $validade->format('d/m/Y') }}</span>
                                            @else
                                                <span class="text-secondary">{{ $validade->format('d/m/Y') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty">
                                                <p class="empty-title">Este produto ainda não foi lançado no estoque</p>
                                                <p class="empty-subtitle text-secondary">Lance o estoque inicial em alguma propriedade para começar a acompanhar.</p>
                                                <div class="empty-action">
                                                    <a href="{{ route('admin.estoque.create') }}" class="btn btn-primary">Lançar estoque</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($estoques->isNotEmpty())
                        <div class="card-footer">
                            <a href="{{ route('admin.estoque.create') }}" class="btn btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1 me-1">
                                    <path d="M12 5l0 14"></path>
                                    <path d="M5 12l14 0"></path>
                                </svg>
                                Lançar estoque
                            </a>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Coluna direita --}}
            <div class="col-lg-4">

                @if ($estoqueBaixoCount > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <span class="avatar avatar-sm bg-red-lt me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 9v4" />
                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                    <path d="M12 16h.01" />
                                </svg>
                            </span>
                            <h3 class="card-title">Estoque baixo</h3>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach ($estoques->filter(fn ($p) => (float) $p->pivot->estoque_minimo > 0 && (float) $p->pivot->estoque_atual <= (float) $p->pivot->estoque_minimo) as $propriedade)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="fw-semibold">{{ $propriedade->nome }}</div>
                                        <span class="badge bg-red-lt">
                                            {{ number_format((float) $propriedade->pivot->estoque_atual, 1, ',', '.') }} {{ $produto->unidade }}
                                        </span>
                                    </div>
                                    <div class="text-secondary small">
                                        mínimo de {{ number_format((float) $propriedade->pivot->estoque_minimo, 1, ',', '.') }} {{ $produto->unidade }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @php
                    $comValidade = $estoques->filter(fn ($p) => $p->pivot->data_validade)
                        ->sortBy(fn ($p) => $p->pivot->data_validade);
                @endphp

                @if ($comValidade->isNotEmpty())
                    <div class="card">
                        <div class="card-header">
                            <span class="avatar avatar-sm bg-orange-lt me-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 7a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12" />
                                    <path d="M16 3l0 4" />
                                    <path d="M8 3l0 4" />
                                    <path d="M4 11l16 0" />
                                </svg>
                            </span>
                            <h3 class="card-title">Validade</h3>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach ($comValidade as $propriedade)
                                @php $validade = \Illuminate\Support\Carbon::parse($propriedade->pivot->data_validade); @endphp
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-semibold">{{ $propriedade->nome }}</div>
                                        <div class="text-secondary small">{{ $validade->format('d/m/Y') }}</div>
                                    </div>
                                    @if ($validade->isPast())
                                        <span class="badge bg-red-lt">Vencido</span>
                                    @elseif ($validade->diffInDays(now()) <= 30)
                                        <span class="badge bg-orange-lt">{{ $validade->diffInDays(now()) }}d</span>
                                    @else
                                        <span class="badge bg-green-lt">Ok</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($estoqueBaixoCount === 0 && $comValidade->isEmpty())
                    <div class="card">
                        <div class="card-body text-center text-secondary py-4">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="icon mb-2">
                                <path d="M5 12l5 5l10 -10"></path>
                            </svg>
                            <div>Nenhum alerta de estoque ou validade no momento.</div>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</main>
@endsection