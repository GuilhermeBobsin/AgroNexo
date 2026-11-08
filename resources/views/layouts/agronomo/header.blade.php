<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ route('agronomo.dashboard') }}"
                class="d-flex align-items-center text-decoration-none"
                aria-label="AgroNexo">

                <svg xmlns="http://www.w3.org/2000/svg"
                    style="height: 45px; width: 45px;"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-tractor text-primary me-2">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 15a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                    <path d="M7 15l0 .01" />
                    <path d="M17 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                    <path d="M10.5 17l6.5 0" />
                    <path d="M20 15.2v-4.2a1 1 0 0 0 -1 -1h-6l-2 -5h-6v6.5" />
                    <path d="M18 5h-1a1 1 0 0 0 -1 1v4" />
                </svg>

                <span class="fw-bold fs-2">
                    AgroNexo
                </span>
            </a>
        </div>
        <div class="navbar-nav flex-row order-md-last align-items-center">
            {{-- Alternador de Tema (Claro / Escuro) --}}
            <div class="d-none d-md-flex me-2">
                <div class="nav-item">
                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" aria-label="Ativar modo escuro"
                        data-bs-original-title="Ativar modo escuro">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-1">
                            <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z">
                            </path>
                        </svg>
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" aria-label="Ativar modo claro"
                        data-bs-original-title="Ativar modo claro">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-1">
                            <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                            <path
                                d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Notificações --}}
            @include('layouts.partials.notificacoes-header')

            {{-- Menu do Usuário --}}
            <div class="nav-item dropdown ms-2">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Abrir menu do usuário">
                    @if (Auth::user()->profile_photo_path)
                        <span class="avatar avatar-sm" style="background-image: url('{{ \Illuminate\Support\Facades\Storage::disk('public')->url(Auth::user()->profile_photo_path) }}')" role="img" aria-label="Foto de {{ Auth::user()->name }}"></span>
                    @else
                        <span class="avatar avatar-sm bg-purple-lt text-purple fw-bold" aria-hidden="true">{{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
                    @endif
                    <div class="d-none d-xl-block ps-2 text-start">
                        <div class="fw-semibold">{{ Auth::user()->name }}</div>
                        <div class="mt-1 small text-secondary">Agrônomo</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2"><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"/><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"/></svg>
                        Meu perfil
                    </a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2"><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"/><path d="M9 12h12l-3 -3"/><path d="M18 15l3 -3"/></svg>
                            Sair
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('layouts.partials.tema-header-script')
</header>

<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu" style="visibility: visible !important;">
        <div class="navbar">
            <div class="container-xl">
                <div class="row flex-column flex-md-row flex-fill align-items-center">
                    <div class="col">
                        <ul class="navbar-nav">
                            {{-- Dashboard --}}
                            <li class="nav-item {{ request()->routeIs(['agronomo.dashboard', 'dashboard.agronomo']) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('agronomo.dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Dashboard</span>
                                </a>
                            </li>

                            {{-- Recomendações Técnicas --}}
                            <li class="nav-item dropdown {{ request()->routeIs('agronomo.recomendacoes.*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-recomendacoes" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2"></path>
                                            <path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z"></path>
                                            <path d="M9 14l2 2l4 -4"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Recomendações</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('agronomo.recomendacoes.index') }}">
                                        Todas as recomendações
                                    </a>
                                    <a class="dropdown-item" href="{{ route('agronomo.recomendacoes.create') }}">
                                        Nova recomendação técnica
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('agronomo.recomendacoes.index', ['status' => 'pendente']) }}">
                                        Aguardando análise do produtor
                                    </a>
                                </div>
                            </li>

                            {{-- Tarefas de Campo --}}
                            <li class="nav-item dropdown {{ request()->routeIs('agronomo.tarefas.*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-tarefas" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M9 11l3 3l8 -8"></path>
                                            <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Tarefas de Campo</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('agronomo.tarefas.index') }}">Todas as tarefas</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('agronomo.tarefas.index', ['status' => 'pendente']) }}">Pendentes</a>
                                    <a class="dropdown-item" href="{{ route('agronomo.tarefas.index', ['status' => 'em_andamento']) }}">Em andamento</a>
                                    <a class="dropdown-item" href="{{ route('agronomo.tarefas.index', ['status' => 'concluida']) }}">Concluídas</a>
                                </div>
                            </li>

                            {{-- Registro de Aplicações --}}
                            <li class="nav-item {{ request()->routeIs('agronomo.aplicacoes.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('agronomo.aplicacoes.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Aplicações</span>
                                </a>
                            </li>

                            {{-- Inteligência & Clima --}}
                            <li class="nav-item {{ request()->routeIs('agronomo.inteligencia.*') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('agronomo.inteligencia.index') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M20 16.2a4.5 4.5 0 0 0 -2.5 -8.5a6 6 0 0 0 -11.5 1.8a3.5 3.5 0 0 0 .5 6.7h13.5"></path>
                                            <path d="M8 19l-1 2M13 19l-1 2M18 19l-1 2"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Inteligência Climática</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
