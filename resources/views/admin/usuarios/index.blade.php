@extends('layouts.admin.base')
@section('content')
<div class="page-wrapper">
    <div class="page-header d-print-none" aria-label="Page header">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Usuários</h2>
                    <div class="text-secondary mt-1">1-18 of 413 people</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex">
                        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary btn-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-2">
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Novo usuário
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                @foreach($usuarios as $usuario)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3"
                                style="background-image: url(./static/avatars/000m.jpg)"> </span>
                            <h3 class="m-0 mb-1"><a href="#">{{ $usuario->name }}</a></h3>
                            <div class="text-secondary">{{ $usuario->email }}</div>
                            <div class="mt-3">
                                @if ($usuario->perfil === 'operador')
                                <span class="badge bg-blue-lt">{{ $usuario->perfil }}</span>
                                @elseif ($usuario->perfil === 'admin')
                                <span class="badge bg-green-lt">{{ $usuario->perfil }}</span>
                                @elseif ($usuario->perfil === 'agronomo')
                                <span class="badge bg-purple-lt">{{ $usuario->perfil }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="#" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon me-2 text-muted icon-3">
                                    <path
                                        d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z">
                                    </path>
                                    <path d="M3 7l9 6l9 -6"></path>
                                </svg>
                                Email
                            </a>
                            <a href="#" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon me-2 text-muted icon-3">
                                    <path
                                        d="M5 4h4l2 5l-2.5 1.5a11 11 0 0 0 5 5l1.5 -2.5l5 2v4a2 2 0 0 1 -2 2a16 16 0 0 1 -15 -15a2 2 0 0 1 2 -2">
                                    </path>
                                </svg>
                                Call
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="d-flex mt-4">
                @if ($usuarios->hasPages())
                <ul class="pagination ms-auto">

                    <li class="page-item {{ $usuarios->onFirstPage() ? 'disabled' : '' }}">
                        @if ($usuarios->onFirstPage())
                        <span class="page-link page-text" aria-disabled="true">
                            Previous
                        </span>
                        @else
                        <a class="page-link page-text" href="{{ $usuarios->previousPageUrl() }}">
                            Previous
                        </a>
                        @endif
                    </li>

                    @foreach ($usuarios->getUrlRange(1, $usuarios->lastPage()) as $page => $url)
                    <li class="page-item {{ $page == $usuarios->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $url }}">
                            {{ $page }}
                        </a>
                    </li>
                    @endforeach

                    <li class="page-item {{ !$usuarios->hasMorePages() ? 'disabled' : '' }}">
                        @if ($usuarios->hasMorePages())
                        <a class="page-link page-text" href="{{ $usuarios->nextPageUrl() }}">
                            Next
                        </a>
                        @else
                        <span class="page-link page-text" aria-disabled="true">
                            Next
                        </span>
                        @endif
                    </li>

                </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection