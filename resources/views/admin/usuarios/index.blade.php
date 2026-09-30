@extends('layouts.admin.base')

@section('content')
<div class="page-wrapper">
    <div class="page-header d-print-none" aria-label="Page header">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center">
                        <span class="avatar bg-blue-lt me-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                            </svg>
                        </span>
                        <div>
                            <h2 class="page-title mb-0">Usuários</h2>
                            <div class="text-secondary"><span id="contagem-usuarios">{{ $contagem }}</span> {{ $contagem === 1 ? 'usuário cadastrado' : 'usuários cadastrados' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 5l0 14"></path><path d="M5 12l14 0"></path></svg>
                        Novo usuário
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                @foreach ($usuarios as $usuario)
                    @php
                        $corPerfil = match ($usuario->perfil) {
                            'admin' => 'green',
                            'agronomo' => 'purple',
                            'operador' => 'blue',
                            default => 'secondary',
                        };
                        $ehVoceMesmo = $usuario->is(auth()->user());
                    @endphp
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 card-usuario" id="usuario-{{ $usuario->id }}">
                            <div class="card-body p-4 text-center">
                                <span class="avatar avatar-xl mb-3 bg-{{ $corPerfil }}-lt" id="avatar-{{ $usuario->id }}">{{ mb_strtoupper(mb_substr($usuario->name, 0, 1)) }}</span>
                                <h3 class="m-0 mb-1" id="nome-{{ $usuario->id }}">{{ $usuario->name }}</h3>
                                <div class="text-secondary text-truncate" id="email-{{ $usuario->id }}">{{ $usuario->email }}</div>
                                <div class="mt-3 d-flex flex-column align-items-center gap-1">
                                    <div class="d-flex gap-1">
                                        <span class="badge bg-{{ $corPerfil }}-lt" id="perfil-{{ $usuario->id }}">{{ ucfirst($usuario->perfil) }}</span>
                                        @if ($ehVoceMesmo)<span class="badge bg-azure-lt">Você</span>@endif
                                    </div>
                                    <span class="badge bg-{{ $usuario->status === 'ativo' ? 'green' : 'secondary' }}-lt" id="status-{{ $usuario->id }}">{{ ucfirst($usuario->status) }}</span>
                                </div>
                            </div>
                            <div class="d-flex">
                                <a href="mailto:{{ $usuario->email }}" class="card-btn" title="Enviar e-mail">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon me-2 text-muted icon-3"><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path><path d="M3 7l9 6l9 -6"></path></svg>
                                    E-mail
                                </a>
                            </div>
                            <div class="card-footer d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary flex-fill btn-editar-usuario"
                                    data-url="{{ route('admin.usuarios.update', $usuario) }}"
                                    data-id="{{ $usuario->id }}" data-nome="{{ $usuario->name }}"
                                    data-email="{{ $usuario->email }}" data-perfil="{{ $usuario->perfil }}"
                                    data-status="{{ $usuario->status }}"
                                    data-propriedade-ids="{{ $usuario->propriedades->pluck('id')->implode(',') }}">Editar</button>
                                @unless ($ehVoceMesmo)
                                    <button type="button" class="btn btn-sm btn-outline-danger flex-fill btn-excluir-usuario"
                                        data-url="{{ route('admin.usuarios.destroy', $usuario) }}" data-id="{{ $usuario->id }}"
                                        data-nome="{{ $usuario->name }}">Excluir</button>
                                @endunless
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex mt-4">{{ $usuarios->links() }}</div>
        </div>
    </div>
</div>

<script type="application/json" id="propriedades-usuarios">@json($propriedades->map(fn ($propriedade) => ['id' => $propriedade->id, 'nome' => $propriedade->nome])->values())</script>
<script type="module" src="{{ asset('js/usuarios/index.js') }}"></script>
@endsection
