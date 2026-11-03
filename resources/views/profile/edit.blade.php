@extends('layouts.admin.base')

@section('content')
    @php
        $perfilUsuario = ['admin' => 'Administrador', 'agronomo' => 'Agrônomo', 'operador' => 'Operador'][$user->perfil] ?? 'Usuário';
        $avatarUrl = $user->profile_photo_path ? \Illuminate\Support\Facades\Storage::disk('public')->url($user->profile_photo_path) : null;
    @endphp

    <main class="page-body">
        <div class="container-xl">
            <div class="page-header d-print-none mb-4">
                <div class="row align-items-center g-3">
                    <div class="col">
                        <div class="page-pretitle">Conta e preferências</div>
                        <h1 class="page-title">Meu perfil</h1>
                        <div class="text-secondary mt-1">Atualize seus dados pessoais, sua foto e a senha de acesso.</div>
                    </div>
                </div>
            </div>

            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible" role="status">
                    Seus dados foram atualizados.
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></a>
                </div>
            @endif
            @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible" role="status">
                    Sua senha foi alterada.
                    <a class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></a>
                </div>
            @endif

            <div class="row row-cards align-items-start">
                <div class="col-12 col-xl-4">
                    <section class="card card-stacked">
                        <div class="card-body text-center py-5">
                            @if ($avatarUrl)
                                <span class="avatar avatar-xl rounded-circle mb-3" style="background-image: url('{{ $avatarUrl }}')" role="img" aria-label="Foto de {{ $user->name }}"></span>
                            @else
                                <span class="avatar avatar-xl rounded-circle bg-primary-lt text-primary mb-3" aria-hidden="true">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                            @endif
                            <h2 class="h2 mb-1">{{ $user->name }}</h2>
                            <div class="text-secondary mb-3">{{ $user->email }}</div>
                            <span class="badge bg-{{ $user->status === 'ativo' ? 'green' : 'secondary' }}-lt">{{ $perfilUsuario }} · {{ ucfirst($user->status) }}</span>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex align-items-center gap-2 text-secondary small">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 3l8 4.5v9L12 21l-8-4.5v-9L12 3z"/><path d="M12 12l8-4.5M12 12v9M12 12L4 7.5"/></svg>
                                Seu acesso e suas permissões são definidos pelo perfil da conta.
                            </div>
                        </div>
                    </section>
                </div>

                <div class="col-12 col-xl-8">
                    <div class="row row-cards">
                        <div class="col-12">
                            @include('profile.partials.update-profile-information-form')
                        </div>
                        <div class="col-12">
                            @include('profile.partials.update-password-form')
                        </div>
                        <div class="col-12">
                            @include('profile.partials.delete-user-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
