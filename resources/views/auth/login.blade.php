@extends('layouts.auth')

@section('title', 'Entrar · AgroNexo')

@section('content')
    <div class="mb-2"><span class="aside-label" style="color:#176b50">Bem-vindo de volta</span></div>
    <h2 class="auth-form-title">Entre na sua conta</h2>
    <p class="auth-form-subtitle">Acesse seu espaço de gestão e continue acompanhando a operação.</p>

    @if (session('status'))
        <div class="auth-alert auth-success" role="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="auth-alert" role="alert">Confira os dados informados e tente novamente.</div>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label auth-label" for="email">E-mail</label>
            <input type="email" id="email" name="email" class="form-control auth-control @error('email') is-invalid @enderror" placeholder="voce@exemplo.com" value="{{ old('email') }}" autocomplete="username" required autofocus>
            @error('email') <div class="auth-error" role="alert">{{ $message }}</div> @enderror
        </div>

        <div class="mb-1">
            <label class="form-label auth-label" for="password">Senha</label>
            <div class="auth-input-wrap">
                <input type="password" id="password" name="password" class="form-control auth-control @error('password') is-invalid @enderror" placeholder="Digite sua senha" autocomplete="current-password" required>
                <button class="password-toggle" type="button" data-password-toggle="password" aria-label="Mostrar senha" aria-pressed="false" title="Mostrar senha">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2.5 12s3.5-7 9.5-7 9.5 7 9.5 7-3.5 7-9.5 7-9.5-7-9.5-7Z"/><circle cx="12" cy="12" r="2.5"/></svg>
                </button>
            </div>
            @error('password') <div class="auth-error" role="alert">{{ $message }}</div> @enderror
        </div>

        <div class="auth-help-row">
            <label class="auth-check" for="remember">
                <input id="remember" type="checkbox" name="remember" value="1">
                <span>Lembrar neste dispositivo</span>
            </label>
            @if (Route::has('password.request'))
                <a class="auth-link" href="{{ route('password.request') }}">Esqueci minha senha</a>
            @endif
        </div>

        <button class="btn auth-submit w-100" type="submit">Entrar <span aria-hidden="true">→</span></button>
    </form>

    <div class="auth-divider">Acesso AgroNexo</div>
    <p class="auth-switch">Ainda não tem conta? <a class="auth-link" href="{{ route('register') }}">Criar conta de produtor</a></p>
    <div class="auth-security-note"><i class="ti ti-shield-lock" aria-hidden="true"></i> Acesso protegido à sua operação agrícola</div>
@endsection
