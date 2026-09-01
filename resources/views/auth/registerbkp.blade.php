@extends('layouts.auth')
@section('content')
<div class="page page-center">
    <div class="container container-normal py-4">
        <div class="row align-items-center g-4">

            <div class="col-lg-6">
                <div class="container-tight">
                    <div class="text-center mb-4">
                        <a href="." aria-label="AgroNexo" class="navbar-brand navbar-brand-autodark">
                            <svg xmlns="http://www.w3.org/2000/svg" style="height: 50px; width: 50px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-tractor text-primary me-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 15a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M7 15l0 .01" />
                                <path d="M17 17a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M10.5 17l6.5 0" />
                                <path d="M20 15.2v-4.2a1 1 0 0 0 -1 -1h-6l-2 -5h-6v6.5" />
                                <path d="M18 5h-1a1 1 0 0 0 -1 1v4" />
                            </svg>
                            <span class="fw-bold fs-2">AgroNexo</span>
                        </a>
                    </div>
                    <div class="card card-md">
                        <div class="card-body">
                            <h2 class="h2 text-center mb-4">Registre sua conta</h2>

                            <form action="{{ route('register') }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Nome completo</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Seu nome" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        placeholder="usuario@email.com" autocomplete="username"
                                        value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Senha</label>
                                    <div class="input-group input-group-flat">
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror"
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
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirmar senha</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Repita a senha" autocomplete="new-password" required>
                                </div>

                                <div class="mb-3 d-lg-none">
                                    <label class="form-label">Você é...</label>
                                    <select name="perfil" class="form-select" required>
                                        <option value="operador" {{ old('perfil', 'operador') === 'operador' ? 'selected' : '' }}>Operador</option>
                                        <option value="agronomo" {{ old('perfil') === 'agronomo' ? 'selected' : '' }}>Agrônomo</option>
                                        <option value="admin" {{ old('perfil') === 'admin' ? 'selected' : '' }}>Administrador</option>
                                    </select>
                                </div>

                                <div class="form-footer">
                                    <button type="submit" class="btn btn-primary w-100">Criar conta</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-center text-secondary mt-3">
                        Já tem uma conta? <a href="{{ route('login') }}" tabindex="-1">Entrar</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block">
                <div class="container-tight">
                    <h3 class="mb-1">Você é...</h3>
                    <p class="text-secondary mb-4">Escolha o perfil que melhor descreve sua função na propriedade.</p>

                    <div class="form-selectgroup form-selectgroup-boxes d-flex flex-column" data-perfil-selector>

                        <label class="form-selectgroup-item flex-fill">
                            <input type="radio" name="perfil" value="admin" class="form-selectgroup-input" {{ old('perfil') === 'admin' ? 'checked' : '' }}>
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
                            <input type="radio" name="perfil" value="agronomo" class="form-selectgroup-input" {{ old('perfil') === 'agronomo' ? 'checked' : '' }}>
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
                            <input type="radio" name="perfil" value="operador" class="form-selectgroup-input" {{ old('perfil', 'operador') === 'operador' ? 'checked' : '' }}>
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
                    @error('perfil')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        var input = btn.closest('.input-group').querySelector('input');
        input.type = input.type === 'password' ? 'text' : 'password';
    });
});
</script>
@endsection