@extends('layouts.admin.base')
@section('content')

<div class="page">
    <div class="page-wrapper">
        <div class="page-body">
            <div class="container-xl">

                <div class="page-header d-print-none mb-4">
                    <h2 class="page-title">Novo usuário</h2>
                    <div class="text-secondary mt-1">Cadastre um acesso ao AgroNexo e defina o que ele pode ver.</div>
                </div>

                <form id="form-criar-usuario" action="{{ route('admin.usuarios.store') }}" method="POST"
                    data-ajax-form data-redirect="{{ route('admin.usuarios.index') }}">
                    @csrf

                    <div class="row g-4">

                        {{-- Coluna esquerda: dados + propriedades --}}
                        <div class="col-lg-7">
                            <div class="card">
                                <div class="card-header">
                                    <span class="avatar avatar-sm bg-blue-lt me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                        </svg>
                                    </span>
                                    <h3 class="card-title">Dados de acesso</h3>
                                </div>
                                <div class="card-body">

                                    <div class="mb-3">
                                        <label class="form-label">Nome completo</label>
                                        <input type="text" name="name" id="input-name" class="form-control"
                                            placeholder="Nome do usuário" required autofocus>
                                        <div class="invalid-feedback" id="error-name"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" id="input-email" class="form-control"
                                            placeholder="usuario@email.com" autocomplete="username" required>
                                        <div class="invalid-feedback" id="error-email"></div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Senha</label>
                                        <div class="input-group input-group-flat">
                                            <input type="password" name="password" id="input-password" class="form-control"
                                                placeholder="Crie uma senha" autocomplete="new-password" required>
                                            <span class="input-group-text">
                                                <a href="#" class="link-secondary toggle-password" aria-label="Mostrar senha">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"></path>
                                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"></path>
                                                    </svg>
                                                </a>
                                            </span>
                                        </div>
                                        <div class="invalid-feedback d-block" id="error-password"></div>
                                    </div>

                                </div>
                            </div>

                            <div class="card mt-4" id="card-propriedades">
                                <div class="card-header">
                                    <span class="avatar avatar-sm bg-purple-lt me-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                            <path d="M3 21l18 0"></path>
                                            <path d="M5 21v-14l8 -4l6 3v15"></path>
                                            <path d="M9 21v-8h4v8"></path>
                                        </svg>
                                    </span>
                                    <h3 class="card-title">Acesso às propriedades</h3>
                                </div>
                                <div class="card-body">
                                    <p class="text-secondary" id="texto-acesso-propriedades">
                                        Selecione as propriedades que este usuário pode acessar.
                                    </p>

                                    <div id="alerta-acesso-total" class="alert alert-info d-none">
                                        Administradores têm acesso a todas as propriedades automaticamente — nenhuma seleção é necessária.
                                    </div>

                                    <div id="lista-propriedades" style="max-height: 280px; overflow-y: auto;">
                                        @forelse ($propriedades as $propriedade)
                                            <label class="form-check mb-2">
                                                <input class="form-check-input" type="checkbox" name="propriedade_ids[]"
                                                    value="{{ $propriedade->id }}"
                                                    @checked(in_array($propriedade->id, old('propriedade_ids', [])))>
                                                <span class="form-check-label">{{ $propriedade->nome }}</span>
                                            </label>
                                        @empty
                                            <div class="text-secondary">Cadastre uma propriedade para conceder acesso.</div>
                                        @endforelse
                                    </div>

                                    <div class="invalid-feedback d-block" id="error-propriedade_ids"></div>
                                </div>
                            </div>

                            <div class="form-footer">
                                <button type="submit" id="btn-salvar-usuario" class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1 me-1">
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>
                                    Criar conta
                                </button>
                            </div>
                        </div>

                        {{-- Coluna direita: perfil --}}
                        <div class="col-lg-5">
                            <div class="text-secondary mb-2 small">Perfil de acesso</div>
                            <div class="card">
                                <div class="card-body">

                                    <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column">

                                        <label class="form-selectgroup-item flex-fill">
                                            <input type="radio" name="perfil" value="admin" class="form-selectgroup-input" id="perfil-admin">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <span class="me-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                        <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                                        <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                        <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                                                        <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                        <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                                                    </svg>
                                                </span>
                                                <span>
                                                    <span class="d-block fw-bold">Administrador</span>
                                                    <span class="d-block text-secondary">Gerencia propriedades, usuários e configurações</span>
                                                </span>
                                            </div>
                                        </label>

                                        <label class="form-selectgroup-item flex-fill mt-2">
                                            <input type="radio" name="perfil" value="agronomo" class="form-selectgroup-input" id="perfil-agronomo">
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <span class="me-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M12 6a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path d="M4 6l8 0" />
                                                        <path d="M16 6l4 0" />
                                                        <path d="M6 12a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path d="M4 12l2 0" />
                                                        <path d="M10 12l10 0" />
                                                        <path d="M15 18a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                        <path d="M4 18l11 0" />
                                                        <path d="M19 18l1 0" />
                                                    </svg>
                                                </span>
                                                <span>
                                                    <span class="d-block fw-bold">Agrônomo</span>
                                                    <span class="d-block text-secondary">Acompanha talhões, clima e valida recomendações</span>
                                                </span>
                                            </div>
                                        </label>

                                        <label class="form-selectgroup-item flex-fill mt-2">
                                            <input type="radio" name="perfil" value="operador" class="form-selectgroup-input" id="perfil-operador" checked>
                                            <div class="form-selectgroup-label d-flex align-items-center p-3">
                                                <span class="me-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-2">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path d="M7.502 19.423c2.602 2.105 6.395 2.105 8.996 0c2.602 -2.105 3.262 -5.708 1.566 -8.546l-4.89 -7.26c-.42 -.625 -1.287 -.803 -1.936 -.397a1.376 1.376 0 0 0 -.41 .397l-4.893 7.26c-1.695 2.838 -1.035 6.441 1.567 8.546" />
                                                    </svg>
                                                </span>
                                                <span>
                                                    <span class="d-block fw-bold">Operador</span>
                                                    <span class="d-block text-secondary">Registra aplicações e executa as tarefas do dia</span>
                                                </span>
                                            </div>
                                        </label>

                                    </div>
                                    <div class="invalid-feedback d-block" id="error-perfil"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script type="module" src="{{ asset('js/usuarios/create.js') }}"></script>
<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const radios = document.querySelectorAll('input[name="perfil"]');
        const listaPropriedades = document.getElementById('lista-propriedades');
        const alertaAcessoTotal = document.getElementById('alerta-acesso-total');
        const textoAcesso = document.getElementById('texto-acesso-propriedades');
        const checkboxes = listaPropriedades.querySelectorAll('input[type="checkbox"]');

        function atualizarAcesso() {
            const admin = document.getElementById('perfil-admin').checked;

            checkboxes.forEach((cb) => (cb.disabled = admin));
            listaPropriedades.classList.toggle('opacity-50', admin);
            alertaAcessoTotal.classList.toggle('d-none', !admin);
            textoAcesso.classList.toggle('d-none', admin);
        }

        radios.forEach((radio) => radio.addEventListener('change', atualizarAcesso));
        atualizarAcesso();
    });
</script>
@endsection