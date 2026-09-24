@extends('layouts.admin.base')
@section('content')
@if (session('success'))<div class="container-xl pt-3"><div class="alert alert-success">{{ session('success') }}</div></div>@endif
@if (session('error'))<div class="container-xl pt-3"><div class="alert alert-danger">{{ session('error') }}</div></div>@endif
<div class="page-wrapper">
    <div class="page-header d-print-none" aria-label="Page header">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">Usuários</h2>
                    <div class="text-secondary mt-1">{{ $contagem }} usuários cadastrados</div>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex">
                        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-primary btn-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-2">
                                <path d="M12 5l0 14"></path>
                                <path d="M5 12l14 0"></path>
                            </svg>
                            Novo usuário
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row row-cards">
                @foreach($usuarios as $usuario)
                <div class="col-md-6 col-lg-3">
                    <div class="card">
                        <div class="card-body p-4 text-center">
                            <span class="avatar avatar-xl mb-3 bg-blue-lt">{{ mb_strtoupper(mb_substr($usuario->name, 0, 1)) }}</span>
                            <h3 class="m-0 mb-1">{{ $usuario->name }}</h3>
                            <div class="text-secondary">{{ $usuario->email }}</div>
                            <div class="mt-3">
                                @if ($usuario->perfil === 'operador')
                                <span class="badge bg-blue-lt">{{ $usuario->perfil }}</span>
                                @elseif ($usuario->perfil === 'admin')
                                <span class="badge bg-green-lt">{{ $usuario->perfil }}</span>
                                @elseif ($usuario->perfil === 'agronomo')
                                <span class="badge bg-purple-lt">{{ $usuario->perfil }}</span>
                                @endif
                                <div class="mt-2"><span class="badge bg-{{ $usuario->status === 'ativo' ? 'green' : 'secondary' }}-lt">{{ ucfirst($usuario->status) }}</span></div>
                            </div>
                        </div>
                        <div class="d-flex">
                            <a href="mailto:{{ $usuario->email }}" class="card-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="icon me-2 text-muted icon-3">
                                    <path
                                        d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z">
                                    </path>
                                    <path d="M3 7l9 6l9 -6"></path>
                                </svg>
                                E-mail
                            </a>
                            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="card-btn">Gerenciar</a>
                        </div>
                        <div class="card-footer d-flex gap-2">
                            <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-primary flex-fill">Editar</a>
                            @if (!$usuario->is(auth()->user()))<form method="POST" action="{{ route('admin.usuarios.destroy', $usuario) }}" onsubmit="return confirm('Excluir {{ addslashes($usuario->name) }}? Usuários com histórico serão preservados e devem ser desativados.')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Excluir</button></form>@endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="d-flex mt-4">{{ $usuarios->links() }}</div>
           
        </div>
    </div>
</div>
@endsection
