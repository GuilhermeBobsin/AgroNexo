@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body">
    <div class="container-xl">

        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Propriedades</h2>
                    <div class="text-secondary mt-1">Gerencie as propriedades cadastradas no AgroNexo.</div>
                </div>

                <div class="col-auto ms-auto">
                    <a href="{{ route('admin.propriedades.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                        </svg>
                        Nova propriedade
                    </a>
                </div>
            </div>
        </div>

        <div class="row row-deck row-cards mb-4">

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-green-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M3 21l18 0"></path>
                                    <path d="M5 21v-14l8 -4l6 3v15"></path>
                                    <path d="M9 21v-8h4v8"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Propriedades</div>
                                <div class="h2 mb-0">{{ $contagem }}</div>
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
                                    <path d="M4 8v-2a2 2 0 0 1 2 -2h2"></path>
                                    <path d="M4 16v2a2 2 0 0 0 2 2h2"></path>
                                    <path d="M16 4h2a2 2 0 0 1 2 2v2"></path>
                                    <path d="M16 20h2a2 2 0 0 0 2 -2v-2"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Área total</div>
                                <div class="h2 mb-0">{{ $areaTotal }} ha</div>
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
                                    <path d="M4 20v-10a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v10"></path>
                                    <path d="M4 20h16"></path>
                                    <path d="M8 4v4"></path>
                                    <path d="M16 4v4"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Talhões</div>
                                <div class="h2 mb-0">{{ $talhoes }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
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
                                <div class="text-secondary">Culturas Plantadas</div>
                                <div class="h2 mb-0">{{ $quantidadeCulturas }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row row-cards">

            @foreach ($propriedades as $propriedade)
            <div class="col-md-6 col-xl-4">
                <div class="card h-100">

                    <div class="card-header">
                        <span class="avatar bg-green-lt me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M12 3v18"></path>
                                <path d="M5 7h14"></path>
                                <path d="M6 7l3 5l-3 5"></path>
                                <path d="M18 7l-3 5l3 5"></path>
                            </svg>
                        </span>

                        <div>
                            <h3 class="card-title">{{ $propriedade->nome }}</h3>
                            <div class="card-subtitle">{{ $propriedade->localizacao }}</div>
                        </div>

                        <div class="card-actions">
                            <div class="dropdown">
                                <a href="#" class="btn-action dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                        <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                        <path d="M12 5m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                        <path d="M12 19m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0"></path>
                                    </svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('admin.propriedades.show', $propriedade->id) }}">Visualizar</a>
                                    <a class="dropdown-item" href="#">Editar</a>
                                    <a class="dropdown-item text-danger" href="#">Excluir</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row align-items-center mb-3">
                            <div class="col">
                                <div class="text-secondary">Área total</div>
                                <div class="h2 mb-0">{{ $propriedade->talhoes()->sum('area') }} ha</div>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-green-lt">Ativa</span>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="text-secondary">Talhões</div>
                                <div class="fw-bold">{{ $propriedade->talhoes()->count() }}</div>
                            </div>
                            <div class="col-6">
                                <div class="text-secondary">Principal cultura</div>
                                <div class="fw-bold text-green">Soja</div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="#" class="btn btn-primary w-100">Ver propriedade</a>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="d-flex mt-4">
                @if ($propriedades->hasPages())
                <ul class="pagination ms-auto">

                    <li class="page-item {{ $propriedades->onFirstPage() ? 'disabled' : '' }}">
                        @if ($propriedades->onFirstPage())
                        <span class="page-link page-text" aria-disabled="true">
                            Anterior
                        </span>
                        @else
                        <a class="page-link page-text" href="{{ $propriedades->previousPageUrl() }}">
                            Anterior
                        </a>
                        @endif
                    </li>

                    @foreach ($propriedades->getUrlRange(1, $propriedades->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $propriedades->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">
                            {{ $page }}
                        </a>
                    </li>
                    @endforeach

                    <li class="page-item {{ !$propriedades->hasMorePages() ? 'disabled' : '' }}">
                        @if ($propriedades->hasMorePages())
                        <a class="page-link page-text" href="{{ $propriedades->nextPageUrl() }}">
                            Próximo
                        </a>
                        @else
                        <span class="page-link page-text" aria-disabled="true">
                            Próximo
                        </span>
                        @endif
                    </li>

                </ul>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection