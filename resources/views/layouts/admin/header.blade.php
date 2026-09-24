<header class="navbar navbar-expand-md d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
            <a href="{{ url('/admin/dashboard') }}"
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
        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item d-none d-md-flex me-3">
                <div class="btn-list">
                    <a href="https://github.com/tabler/tabler" class="btn btn-5" target="_blank" rel="noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-2">
                            <path
                                d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5">
                            </path>
                        </svg>
                        Source code
                    </a>
                    <a href="https://github.com/sponsors/codecalm" class="btn btn-6" target="_blank" rel="noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon text-pink icon-2">
                            <path d="M19.5 12.572l-7.5 7.428l-7.5 -7.428a5 5 0 1 1 7.5 -6.566a5 5 0 1 1 7.5 6.572">
                            </path>
                        </svg>
                        Sponsor
                    </a>
                </div>
            </div>
            <div class="d-none d-md-flex">
                <div class="nav-item">
                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" aria-label="Enable dark mode"
                        data-bs-original-title="Enable dark mode">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-1">
                            <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z">
                            </path>
                        </svg>
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" aria-label="Enable light mode"
                        data-bs-original-title="Enable light mode">
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
                <div class="nav-item dropdown d-none d-md-flex">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                        aria-label="Show notifications" data-bs-auto-close="outside" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-1">
                            <path
                                d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6">
                            </path>
                            <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                        </svg>
                        <span class="badge bg-red"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                        <div class="card">
                            <div class="card-header d-flex">
                                <h3 class="card-title">Notifications</h3>
                                <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable">
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span
                                                class="status-dot status-dot-animated bg-red d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 1</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">Change deprecated
                                                html tags to text decoration classes (#29604)</div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon text-muted icon-2">
                                                    <path
                                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 2</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">
                                                justify-content:between ⇒ justify-content:space-between (#29734)</div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions show">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon text-yellow icon-2">
                                                    <path
                                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 3</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">Update
                                                change-version.js (#29736)</div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon text-muted icon-2">
                                                    <path
                                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span
                                                class="status-dot status-dot-animated bg-green d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 4</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">Regenerate
                                                package-lock.json (#29730)</div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon text-muted icon-2">
                                                    <path
                                                        d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z">
                                                    </path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <a href="#" class="btn btn-2 w-100"> Archive all </a>
                                    </div>
                                    <div class="col">
                                        <a href="#" class="btn btn-2 w-100"> Mark all as read </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nav-item dropdown d-none d-md-flex me-3">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show app menu"
                        data-bs-auto-close="outside" aria-expanded="false">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="icon icon-1">
                            <path d="M4 4m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                            </path>
                            <path d="M4 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                            </path>
                            <path d="M14 14m0 1a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4a1 1 0 0 1 -1 1h-4a1 1 0 0 1 -1 -1z">
                            </path>
                            <path d="M14 7l6 0"></path>
                            <path d="M17 4l0 6"></path>
                        </svg>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">My Apps</div>
                                <div class="card-actions btn-actions">
                                    <a href="#" class="btn-action">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path
                                                d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                            </path>
                                            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body scroll-y p-2" style="max-height: 50vh">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/amazon.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="">
                                            <span class="h5">Amazon</span>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/android.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="">
                                            <span class="h5">Android</span>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/app-store.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="">
                                            <span class="h5">Apple App Store</span>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/apple-podcast.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="">
                                            <span class="h5">Apple Podcast</span>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/apple.svg" class="w-6 h-6 mx-auto mb-2" width="24"
                                                height="24" alt="">
                                            <span class="h5">Apple</span>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/behance.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="">
                                            <span class="h5">Behance</span>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/discord.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="">
                                            <span class="h5">Discord</span>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/dribbble.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="">
                                            <span class="h5">Dribbble</span>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/dropbox.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="">
                                            <span class="h5">Dropbox</span>
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/ever-green.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="">
                                            <span class="h5">Ever Green</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"> </span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ Auth::user()->name }}</div>
                        <div class="mt-1 small text-secondary">{{ Auth::user()->perfil }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('profile.edit') }}" class="dropdown-item">Meu perfil</a>
                    <div class="dropdown-divider"></div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">Sair</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu" style="visibility: visible !important;">
        <div class="navbar">
            <div class="container-xl">
                <div class="row flex-column flex-md-row flex-fill align-items-center">
                    <div class="col">
                        <ul class="navbar-nav">
                            <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Dashboard </span>
                                </a>
                            </li>
                            <li class="nav-item dropdown {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"></path>
                                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"></path>
                                            <path d="M15 15l3.35 3.35"></path>
                                            <path d="M9 15l-3.35 3.35"></path>
                                            <path d="M5.65 5.65l3.35 3.35"></path>
                                            <path d="M18.35 5.65l-3.35 3.35"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Usuários </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.usuarios.index') }}"> Todos os usuários </a>
                                    <a class="dropdown-item" href="{{ route('admin.usuarios.create') }}"> Novo usuário </a>

                                </div>
                            </li>
                            <li class="nav-item dropdown {{ request()->routeIs(['admin.propriedades.*', 'admin.culturas.*']) ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-map-2">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 18.5l-3 -1.5l-6 3v-13l6 -3l6 3l6 -3v7.5" />
                                            <path d="M9 4v13" />
                                            <path d="M15 7v5.5" />
                                            <path d="M21.121 20.121a3 3 0 1 0 -4.242 0c.418 .419 1.125 1.045 2.121 1.879c1.051 -.89 1.759 -1.516 2.121 -1.879" />
                                            <path d="M19 18v.01" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Propriedades </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="{{ route('admin.propriedades.index') }}"> Todas as propriedades </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ route('admin.culturas.index') }}"> Culturas </a>
                                        </div>
                            </li>
                            <li class="nav-item dropdown {{ request()->routeIs('admin.estoque.*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-help" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-stack-2">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 4l-8 4l8 4l8 -4l-8 -4" />
                                            <path d="M4 12l8 4l8 -4" />
                                            <path d="M4 16l8 4l8 -4" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Estoque </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.produtos.index') }}"> Ver produtos </a>
                                    <a class="dropdown-item" href="{{ route('admin.estoque.index') }}"> Ver estoque </a>
                                </div>
                            </li>

                            <li class="nav-item dropdown {{ request()->routeIs('admin.recursos.*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-recursos" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M14.7 6.3a5 5 0 0 0-6.4 6.4l-4.8 4.8a2 2 0 0 0 2.8 2.8l4.8-4.8a5 5 0 0 0 6.4-6.4l-3 3l-3-3z"/><path d="M5 5l3 3"/></svg></span>
                                    <span class="nav-link-title">Recursos</span>
                                </a>
                                <div class="dropdown-menu"><a class="dropdown-item" href="{{ route('admin.recursos.index') }}">Todos os recursos</a><a class="dropdown-item" href="{{ route('admin.recursos.create') }}">Novo recurso</a></div>
                            </li>
                            <li class="nav-item dropdown {{ request()->routeIs('admin.tarefas.*') ? 'active' : '' }}">
                                <a class="nav-link dropdown-toggle" href="#navbar-tarefas" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M9 11l3 3l8-8"/><path d="M20 12v6a2 2 0 0 1-2 2h-12a2 2 0 0 1-2-2v-12a2 2 0 0 1 2-2h9"/></svg></span>
                                    <span class="nav-link-title">Tarefas</span>
                                </a>
                                <div class="dropdown-menu"><a class="dropdown-item" href="{{ route('admin.tarefas.index') }}">Todas as tarefas</a><a class="dropdown-item" href="{{ route('admin.tarefas.create') }}">Nova tarefa</a><a class="dropdown-item" href="{{ route('admin.aplicacoes.index') }}">Registro de aplicações</a></div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-agenda" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M4 7h16v13H4z"/><path d="M8 3v8"/><path d="M16 3v8"/><path d="M4 11h16"/><path d="M8 15h.01"/><path d="M12 15h.01"/></svg></span>
                                    <span class="nav-link-title">Agenda</span>
                                </a>
                                <div class="dropdown-menu"><span class="dropdown-item text-secondary">Calendário · integração futura</span><a class="dropdown-item" href="{{ route('admin.tarefas.index') }}">Tarefas</a><a class="dropdown-item" href="{{ route('admin.tarefas.index', ['status' => 'pendente']) }}">Pendências</a><a class="dropdown-item" href="{{ route('admin.tarefas.create') }}">Nova tarefa</a></div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-clima" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M7 18a4.6 4.4 0 0 1 0-9a5 4.5 0 0 1 9.7-1.5a4 4 0 0 1 2.3 7.5"/><path d="M11 15l-1 5"/><path d="M16 15l-1 5"/></svg></span>
                                    <span class="nav-link-title">Clima</span>
                                </a>
                                <div class="dropdown-menu"><span class="dropdown-item text-secondary">Clima atual · integração pendente</span><span class="dropdown-item text-secondary">Previsão · integração pendente</span><span class="dropdown-item text-secondary">Condições para aplicação · integração pendente</span></div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-inteligencia" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 8a3 3 0 1 0 0 6a3 3 0 0 0 0-6z"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1l-1.7 1.7l-.1-.1a1.7 1.7 0 0 0-1.9-.3a1.7 1.7 0 0 0-1 1.5v.2h-2.4v-.2a1.7 1.7 0 0 0-1-1.5a1.7 1.7 0 0 0-1.9.3l-.1.1L8 17l.1-.1A1.7 1.7 0 0 0 8.4 15a1.7 1.7 0 0 0-1.5-1H6.7v-2.4h.2a1.7 1.7 0 0 0 1.5-1a1.7 1.7 0 0 0-.3-1.9L8 8.6l1.7-1.7l.1.1a1.7 1.7 0 0 0 1.9.3a1.7 1.7 0 0 0 1-1.5v-.2h2.4v.2a1.7 1.7 0 0 0 1 1.5a1.7 1.7 0 0 0 1.9-.3l.1-.1l1.7 1.7l-.1.1a1.7 1.7 0 0 0-.3 1.9a1.7 1.7 0 0 0 1.5 1h.2V14h-.2a1.7 1.7 0 0 0-1.5 1z"/></svg></span>
                                    <span class="nav-link-title">Inteligência</span>
                                </a>
                                <div class="dropdown-menu"><span class="dropdown-item text-secondary">Alertas climáticos · planejamento</span><span class="dropdown-item text-secondary">Regras · planejamento</span><span class="dropdown-item text-secondary">Histórico de recomendações · planejamento</span><span class="dropdown-item text-secondary">Análise de aplicações · planejamento</span></div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-custos" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg></span>
                                    <span class="nav-link-title">Custos</span>
                                </a>
                                <div class="dropdown-menu"><span class="dropdown-item text-secondary">Visão geral · planejamento</span><span class="dropdown-item text-secondary">Custos das aplicações · planejamento</span><span class="dropdown-item text-secondary">Por talhão · planejamento</span><span class="dropdown-item text-secondary">Por período · planejamento</span></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
