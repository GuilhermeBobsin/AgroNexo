@extends('layouts.admin.base')

@section('content')
<main class="page-body">
    <div class="container-xl">
        <div class="page-header mb-4">
            <div>
                <h2 class="page-title">Editar usuário</h2>
                <div class="text-secondary">Atualize os dados de {{ $user->name }}.</div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <form id="form-editar-usuario" method="POST" action="{{ route('admin.usuarios.update', $user) }}" data-ajax-form data-redirect="{{ route('admin.usuarios.index') }}">
                    @csrf @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="name">Nome</label>
                                <input id="name" name="name" class="form-control" value="{{ $user->name }}" required>
                                <div class="invalid-feedback" id="error-name"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="email">E-mail</label>
                                <input id="email" name="email" type="email" class="form-control" value="{{ $user->email }}" required>
                                <div class="invalid-feedback" id="error-email"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="perfil">Perfil</label>
                                    <select id="perfil" name="perfil" class="form-select">
                                        @foreach (['operador' => 'Operador', 'agronomo' => 'Agrônomo', 'admin' => 'Administrador'] as $value => $label)
                                            <option value="{{ $value }}" @selected($user->perfil === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="error-perfil"></div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label" for="status">Acesso</label>
                                    <select id="status" name="status" class="form-select">
                                        <option value="ativo" @selected($user->status === 'ativo')>Ativo</option>
                                        <option value="inativo" @selected($user->status === 'inativo')>Inativo</option>
                                    </select>
                                    <div class="invalid-feedback" id="error-status"></div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Propriedades permitidas</label>
                                <p class="form-hint">Administradores têm acesso global. Para os demais perfis, marque as propriedades acessíveis.</p>
                                @forelse ($propriedades as $propriedade)
                                    <label class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="propriedade_ids[]" value="{{ $propriedade->id }}" @checked(in_array($propriedade->id, $propriedadesSelecionadas))>
                                        <span class="form-check-label">{{ $propriedade->nome }}</span>
                                    </label>
                                @empty
                                    <div class="text-secondary">Nenhuma propriedade cadastrada.</div>
                                @endforelse
                                <div class="invalid-feedback d-block" data-error="propriedade_ids[]"></div>
                            </div>
                            <hr>
                            <p class="text-secondary">Para trocar a senha, preencha os dois campos. Deixe em branco para manter a atual.</p>
                            <div class="mb-3">
                                <label class="form-label" for="password">Nova senha</label>
                                <input id="password" name="password" type="password" class="form-control" autocomplete="new-password">
                                <div class="invalid-feedback" id="error-password"></div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="password_confirmation">Confirme a nova senha</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.usuarios.index') }}" class="btn">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
<script type="module" src="{{ asset('js/usuarios/edit.js') }}"></script>
@endsection
