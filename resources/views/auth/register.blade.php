@extends('layouts.auth')
@section('title', 'Criar conta · AgroNexo')
@section('content')
<div class="page page-center"><div class="container container-tight py-4">
    <div class="text-center mb-4"><a href="{{ route('login') }}" class="navbar-brand">AgroNexo</a></div>
    <form class="card card-md" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Crie sua conta</h2>
            <p class="text-secondary">Sua conta terá acesso de administrador. Depois, cadastre sua propriedade no sistema.</p>
            <div class="mb-3">
                <label class="form-label" for="email">E-mail</label>
                <input class="form-control @error('email') is-invalid @enderror" id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required autofocus>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label" for="password">Senha (mínimo 12 caracteres)</label>
                <input class="form-control @error('password') is-invalid @enderror" id="password" name="password" type="password" autocomplete="new-password" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button class="btn btn-primary w-100" type="submit">Criar conta</button>
        </div>
    </form>
    <div class="text-center text-secondary mt-3">Já tem conta? <a href="{{ route('login') }}">Entrar</a></div>
</div></div>
@endsection
