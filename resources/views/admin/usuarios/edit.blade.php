@extends('layouts.admin.base')
@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h2 class="page-title">Editar usuário</h2><div class="text-secondary">Atualize os dados de {{ $user->name }}.</div></div></div>
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="row"><div class="col-lg-8"><form method="POST" action="{{ route('admin.usuarios.update', $user) }}">@csrf @method('PUT')
        <div class="card"><div class="card-body">
            <div class="mb-3"><label class="form-label" for="name">Nome</label><input id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>@error('name')<div class="text-danger">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="email">E-mail</label><input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required>@error('email')<div class="text-danger">{{ $message }}</div>@enderror</div>
            <div class="row"><div class="col-md-6 mb-3"><label class="form-label" for="perfil">Perfil</label><select id="perfil" name="perfil" class="form-select">@foreach (['operador' => 'Operador', 'agronomo' => 'Agrônomo', 'admin' => 'Administrador'] as $value => $label)<option value="{{ $value }}" @selected(old('perfil', $user->perfil) === $value)>{{ $label }}</option>@endforeach</select>@error('perfil')<div class="text-danger">{{ $message }}</div>@enderror</div>
            <div class="col-md-6 mb-3"><label class="form-label" for="status">Acesso</label><select id="status" name="status" class="form-select"><option value="ativo" @selected(old('status', $user->status) === 'ativo')>Ativo</option><option value="inativo" @selected(old('status', $user->status) === 'inativo')>Inativo</option></select>@error('status')<div class="text-danger">{{ $message }}</div>@enderror</div></div>
            <div class="mb-3"><label class="form-label">Propriedades permitidas</label><p class="form-hint">Administradores têm acesso global. Para os demais perfis, marque as propriedades acessíveis.</p>
                @forelse ($propriedades as $propriedade)
                    <label class="form-check mb-2"><input class="form-check-input" type="checkbox" name="propriedade_ids[]" value="{{ $propriedade->id }}" @checked(in_array($propriedade->id, old('propriedade_ids', $propriedadesSelecionadas)))><span class="form-check-label">{{ $propriedade->nome }}</span></label>
                @empty<div class="text-secondary">Nenhuma propriedade cadastrada.</div>@endforelse
            </div>
            <hr><p class="text-secondary">Para trocar a senha, preencha os dois campos. Deixe em branco para manter a atual.</p>
            <div class="mb-3"><label class="form-label" for="password">Nova senha</label><input id="password" name="password" type="password" class="form-control" autocomplete="new-password">@error('password')<div class="text-danger">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="password_confirmation">Confirme a nova senha</label><input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password"></div>
        </div><div class="card-footer d-flex justify-content-end gap-2"><a href="{{ route('admin.usuarios.index') }}" class="btn">Cancelar</a><button class="btn btn-primary">Salvar alterações</button></div></div>
    </form></div></div>
</div></main>
@endsection
