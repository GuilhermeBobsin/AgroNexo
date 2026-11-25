<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ route('operador.dashboard') }}"
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
            <div class="d-flex me-2">
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
                        <span class="avatar avatar-sm bg-azure-lt text-azure fw-bold" aria-hidden="true">{{ mb_strtoupper(mb_substr(Auth::user()->name, 0, 1)) }}</span>
                    @endif
                    <div class="d-none d-xl-block ps-2 text-start">
                        <div class="fw-semibold">{{ Auth::user()->name }}</div>
                        <div class="mt-1 small text-secondary">Operador</div>
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
                            <li class="nav-item {{ request()->routeIs(['operador.dashboard', 'dashboard.operador']) ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('operador.dashboard') }}">
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

                            {{-- Minhas Tarefas --}}
                            <li class="nav-item dropdown {{ request()->routeIs('operador.tarefas.*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-tarefas" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M9 11l3 3l8 -8"></path>
                                            <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title">Minhas Tarefas</span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('operador.tarefas.index') }}">
                                        Todas as tarefas atribuídas
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="{{ route('operador.tarefas.index', ['status' => 'pendente']) }}">
                                        Aguardando início (Pendentes)
                                    </a>
                                    <a class="dropdown-item" href="{{ route('operador.tarefas.index', ['status' => 'em_andamento']) }}">
                                        Em execução (Em andamento)
                                    </a>
                                    <a class="dropdown-item" href="{{ route('operador.tarefas.index', ['status' => 'concluida']) }}">
                                        Finalizadas (Concluídas)
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
