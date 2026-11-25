@extends('layouts.auth')

@section('title', 'Criar conta · AgroNexo')

@section('content')
    <div class="mb-2"><span class="aside-label" style="color:#176b50">Comece por aqui</span></div>
    <h2 class="auth-form-title">Crie sua conta</h2>
    <p class="auth-form-subtitle">Cadastre seu acesso de produtor. Depois, você poderá criar sua propriedade e organizar sua equipe.</p>

    @if ($errors->any())
        <div class="auth-alert" role="alert">Confira os campos destacados e tente novamente.</div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label auth-label" for="email">E-mail</label>
            <input class="form-control auth-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="voce@exemplo.com" autocomplete="email" required autofocus>
            @error('email') <div class="auth-error" role="alert">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label auth-label" for="password">Senha <span class="text-secondary fw-normal">(mínimo 12 caracteres)</span></label>
            <div class="auth-input-wrap">
                <input class="form-control auth-control @error('password') is-invalid @enderror" id="password" name="password" type="password" placeholder="Crie uma senha segura" autocomplete="new-password" required>
                <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Mostrar senha" aria-pressed="false" title="Mostrar senha">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                </button>
            </div>
            @error('password') <div class="auth-error" role="alert">{{ $message }}</div> @enderror
        </div>

        <button class="btn auth-submit w-100" type="submit">Criar conta <span aria-hidden="true">→</span></button>
    </form>

    <div class="auth-divider">Sua fazenda começa com você</div>
    <p class="auth-switch">Já tem conta? <a class="auth-link" href="{{ route('login') }}">Entrar</a></p>
    <div class="auth-security-note"><i class="ti ti-shield-lock" aria-hidden="true"></i> Seu cadastro cria um espaço próprio para sua operação</div>
@endsection
