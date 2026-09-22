@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body">
    <div class="container-xl">

        {{-- Cabeçalho --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Culturas</h2>
                    <div class="text-secondary mt-1">Gerencie as culturas usadas nos talhões do AgroNexo.</div>
                </div>

                <div class="col-auto ms-auto">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-nova-cultura">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                        </svg>
                        Nova cultura
                    </button>
                </div>
            </div>
        </div>

        {{-- Resumo --}}
        <div class="row row-deck row-cards mb-4">

            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-orange-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 3v18"></path>
                                    <path d="M5 7h14"></path>
                                    <path d="M6 7l3 5l-3 5"></path>
                                    <path d="M18 7l-3 5l3 5"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Culturas cadastradas</div>
                                <div class="h2 mb-0">{{ $culturas->total() }}</div>
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
                                    <path d="M13 20l-3 -3h-3a1 1 0 0 1 -1 -1v-10a1 1 0 0 1 1 -1h14a1 1 0 0 1 1 1v10a1 1 0 0 1 -1 1h-3l-3 3" />
                                    <path d="M9 10.5l2 2l4 -4.5" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Mais usada</div>
                                <div class="h2 mb-0">{{ $culturaMaisUsada?->nome ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-{{ $talhoesSemCultura > 0 ? 'red' : 'secondary' }}-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M12 9v4" />
                                    <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                                    <path d="M12 16h.01" />
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Talhões sem cultura</div>
                                <div class="h2 mb-0">{{ $talhoesSemCultura }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Tabela --}}
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Talhões vinculados</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($culturas as $cultura)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="avatar avatar-sm bg-orange-lt me-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M12 3v18"></path>
                                                <path d="M5 7h14"></path>
                                                <path d="M6 7l3 5l-3 5"></path>
                                                <path d="M18 7l-3 5l3 5"></path>
                                            </svg>
                                        </span>
                                        <span class="fw-bold">{{ $cultura->nome }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $cultura->talhoes_count > 0 ? 'bg-purple-lt' : 'bg-secondary-lt' }}">
                                        {{ $cultura->talhoes_count }} {{ Str::plural('talhão', $cultura->talhoes_count) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-list flex-nowrap">
                                        <button type="button" class="btn btn-icon btn-sm btn-editar-cultura"
                                            data-bs-toggle="modal" data-bs-target="#modal-editar-cultura"
                                            data-url="{{ route('admin.culturas.update', $cultura) }}"
                                            data-nome="{{ $cultura->nome }}"
                                            aria-label="Editar {{ $cultura->nome }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                                <path d="M16 5l3 3" />
                                            </svg>
                                        </button>

                                        <button type="button" class="btn btn-icon btn-sm text-danger btn-excluir-cultura"
                                            data-url="{{ route('admin.culturas.destroy', $cultura) }}"
                                            data-nome="{{ $cultura->nome }}"
                                            data-talhoes="{{ $cultura->talhoes_count }}"
                                            aria-label="Excluir {{ $cultura->nome }}">
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
                                <td colspan="3" class="text-center text-secondary py-5">
                                    Nenhuma cultura cadastrada ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($culturas->hasPages())
            <div class="d-flex mt-4">
                <ul class="pagination ms-auto">
                    <li class="page-item {{ $culturas->onFirstPage() ? 'disabled' : '' }}">
                        @if ($culturas->onFirstPage())
                            <span class="page-link page-text" aria-disabled="true">Anterior</span>
                        @else
                            <a class="page-link page-text" href="{{ $culturas->previousPageUrl() }}">Anterior</a>
                        @endif
                    </li>

                    @foreach ($culturas->getUrlRange(1, $culturas->lastPage()) as $page => $url)
                        <li class="page-item {{ $page == $culturas->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                        </li>
                    @endforeach

                    <li class="page-item {{ !$culturas->hasMorePages() ? 'disabled' : '' }}">
                        @if ($culturas->hasMorePages())
                            <a class="page-link page-text" href="{{ $culturas->nextPageUrl() }}">Próximo</a>
                        @else
                            <span class="page-link page-text" aria-disabled="true">Próximo</span>
                        @endif
                    </li>
                </ul>
            </div>
        @endif

    </div>
</main>

{{-- Modal: nova cultura --}}
<div class="modal modal-blur fade" id="modal-nova-cultura" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <<form id="form-nova-cultura" method="POST" action="{{ route('admin.culturas.store') }}" data-ajax-form>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nova cultura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nome da cultura</label>
                    <input type="text" name="nome" id="input-nome" class="form-control" placeholder="Ex: Soja" required autofocus>
                    <div class="invalid-feedback" id="error-nome"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Criar cultura</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal: editar cultura --}}
<div class="modal modal-blur fade" id="modal-editar-cultura" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" id="form-editar-cultura" data-ajax-form>
                @csrf
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-header">
                    <h5 class="modal-title">Editar cultura</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nome da cultura</label>
                    <input type="text" name="nome" id="edit-input-nome" class="form-control" required>
                    <div class="invalid-feedback" id="edit-error-nome"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="module" src="{{ asset('js/culturas/index.js') }}"></script>
@endsection