@extends('layouts.auth')
@section('title', 'Criar conta · AgroNexo')
@section('content')
<div class="page page-center"><div class="container container-tight py-4">
    <div class="text-center mb-4"><a href="{{ route('login') }}" class="navbar-brand">AgroNexo</a></div>
    <form class="card card-md" method="POST" action="{{ route('register') }}">
        @csrf
        <div class="card-body">
            <h2 class="card-title text-center mb-4">Cadastre sua conta e fazenda</h2>
            <p class="text-secondary">Você será o administrador da propriedade criada neste cadastro.</p>
            @foreach (['name' => ['Seu nome', 'text'], 'email' => ['E-mail', 'email'], 'propriedade_nome' => ['Nome da fazenda/propriedade', 'text'], 'propriedade_localizacao' => ['Município e estado', 'text'], 'password' => ['Senha (mínimo 12 caracteres)', 'password'], 'password_confirmation' => ['Confirme a senha', 'password']] as $campo => [$rotulo, $tipo])
                <div class="mb-3"><label class="form-label" for="{{ $campo }}">{{ $rotulo }}</label><input class="form-control @error($campo) is-invalid @enderror" id="{{ $campo }}" name="{{ $campo }}" type="{{ $tipo }}" value="{{ $tipo === 'password' ? '' : old($campo) }}" autocomplete="{{ $campo === 'email' ? 'email' : ($tipo === 'password' ? 'new-password' : 'name') }}" required>@error($campo)<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
            @endforeach
            <button class="btn btn-primary w-100" type="submit">Criar conta</button>
        </div>
    </form>
    <div class="text-center text-secondary mt-3">Já tem conta? <a href="{{ route('login') }}">Entrar</a></div>
</div></div>
@endsection
