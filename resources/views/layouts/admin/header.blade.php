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
                    <a href="#" class="dropdown-item">Status</a>
                    <a href="./profile.html" class="dropdown-item">Profile</a>
                    <a href="#" class="dropdown-item">Feedback</a>
                    <div class="dropdown-divider"></div>
                    <a href="./settings.html" class="dropdown-item">Settings</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
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
                            <li class="nav-item active">
                                <a class="nav-link" href="./">
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
                            <li class="nav-item dropdown">
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
                                    <a class="dropdown-item" href="#"> Perfis e acessos </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
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
                                            <a class="dropdown-item" href="#"> Todas as propriedades </a>
                                            <a class="dropdown-item" href="#"> Nova propriedade </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#"> Talhões </a>
                                            <a class="dropdown-item" href="#"> Culturas </a>
                                        </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-form" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path d="M9 11l3 3l8 -8"></path>
                                            <path d="M20 12v6a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h9">
                                            </path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Aplicações </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#"> Todas as aplicações </a>
                                    <a class="dropdown-item" href="#"> Nova aplicação </a>
                                    <a class="dropdown-item" href="#"> Aplicações planejadas </a>
                                    <a class="dropdown-item" href="#"> Histórico </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-sun">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M8 12a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                            <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Clima </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="#"> Clima atual </a>
                                            <a class="dropdown-item" href="#"> Previsão </a>
                                            <a class="dropdown-item" href="#"> Condições para aplicação </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-layout" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brain">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M15.5 13a3.5 3.5 0 0 0 -3.5 3.5v1a3.5 3.5 0 0 0 7 0v-1.8" />
                                            <path d="M8.5 13a3.5 3.5 0 0 1 3.5 3.5v1a3.5 3.5 0 0 1 -7 0v-1.8" />
                                            <path d="M17.5 16a3.5 3.5 0 0 0 0 -7h-.5" />
                                            <path d="M19 9.3v-2.8a3.5 3.5 0 0 0 -7 0" />
                                            <path d="M6.5 16a3.5 3.5 0 0 1 0 -7h.5" />
                                            <path d="M5 9.3v-2.8a3.5 3.5 0 0 1 7 0v10" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Inteligência </span>
                                </a>
                                <div class="dropdown-menu">
                                    <div class="dropdown-menu-columns">
                                        <div class="dropdown-menu-column">
                                            <a class="dropdown-item" href="#"> Alertas climáticos </a>
                                            <a class="dropdown-item" href="#"> Regras </a>
                                            <a class="dropdown-item" href="#"> Histórico de recomendações </a>
                                            <a class="dropdown-item" href="#"> Análise de aplicações </a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-plugins" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-list-check">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M3.5 5.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M3.5 11.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M3.5 17.5l1.5 1.5l2.5 -2.5" />
                                            <path d="M11 6l9 0" />
                                            <path d="M11 12l9 0" />
                                            <path d="M11 18l9 0" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Agenda </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#"> Calendário </a>
                                    <a class="dropdown-item" href="#"> Tarefas </a>
                                    <a class="dropdown-item" href="#"> Pendências </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#navbar-addons" data-bs-toggle="dropdown"
                                    data-bs-auto-close="outside" role="button" aria-expanded="false">
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-cash-banknote-edit">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M11 18h-6a2 2 0 0 1 -2 -2v-8a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v3" />
                                            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
                                            <path d="M6 12h.01" />
                                            <path d="M18.42 15.61a2.1 2.1 0 1 1 2.97 2.97l-3.39 3.42h-3v-3l3.42 -3.39" />
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Custos </span>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="#"> Visão geral </a>
                                    <a class="dropdown-item" href="#"> Custos das aplicações </a>
                                    <a class="dropdown-item" href="#"> Por talhão </a>
                                    <a class="dropdown-item" href="#"> Por período </a>
                                </div>
                            </li>
                            <li class="nav-item dropdown">
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
                                    <a class="dropdown-item" href="#"> Produtos </a>
                                    <a class="dropdown-item" href="#"> Estoque atual </a>
                                    <a class="dropdown-item" href="#"> Estoque baixo </a>
                                    <a class="dropdown-item" href="#"> Vencimentos </a>
                                </div>
                            </li>

                        </ul>
                    </div>
                    <!-- <div class="col col-md-auto">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link" href="#" data-bs-toggle="offcanvas"
                                    data-bs-target="#offcanvasSettings">
                                    <span class="badge badge-sm bg-red text-red-fg">New</span>
                                    <span class="nav-link-icon d-md-none d-lg-inline-block">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                            <path
                                                d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z">
                                            </path>
                                            <path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"></path>
                                        </svg>
                                    </span>
                                    <span class="nav-link-title"> Theme Settings </span>
                                </a>
                            </li>
                        </ul>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</header>