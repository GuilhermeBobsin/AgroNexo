@php
    $notificacoesHeader = auth()->user()->notifications()->latest()->limit(6)->get();
    $naoLidasHeader = auth()->user()->unreadNotifications()->count();
@endphp
<div class="nav-item dropdown d-none d-md-flex">
    <a href="#" class="nav-link px-0 position-relative" data-bs-toggle="dropdown" tabindex="-1" aria-label="Notificações" data-bs-auto-close="outside" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1"><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"/><path d="M9 17v1a3 3 0 0 0 6 0v-1"/></svg>
        @if ($naoLidasHeader > 0)<span class="badge bg-red">{{ $naoLidasHeader > 9 ? '9+' : $naoLidasHeader }}</span>@endif
    </a>
    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
        <div class="card">
            <div class="card-header d-flex"><h3 class="card-title">Notificações</h3><span class="badge bg-blue-lt ms-auto">{{ $naoLidasHeader }} não lidas</span></div>
            <div class="list-group list-group-flush list-group-hoverable">
                @forelse ($notificacoesHeader as $notificacaoHeader)
                    <a href="{{ route('notificacoes.abrir', $notificacaoHeader->id) }}" class="list-group-item text-decoration-none">
                        <div class="row align-items-center"><div class="col-auto"><span class="status-dot d-block {{ $notificacaoHeader->read_at ? '' : 'bg-primary' }}"></span></div><div class="col text-truncate"><span class="text-body d-block">{{ $notificacaoHeader->data['titulo'] ?? 'Atualização' }}</span><span class="d-block text-secondary text-truncate mt-n1">{{ $notificacaoHeader->data['mensagem'] ?? '' }}</span><span class="small text-secondary">{{ $notificacaoHeader->created_at->diffForHumans() }}</span></div></div>
                    </a>
                @empty
                    <div class="list-group-item text-center text-secondary py-4">Nenhuma notificação nova.</div>
                @endforelse
            </div>
            @if ($naoLidasHeader > 0)
                <div class="card-body"><form method="POST" action="{{ route('notificacoes.marcar-todas-lidas') }}">@csrf<button class="btn btn-2 w-100">Marcar todas como lidas</button></form></div>
            @endif
        </div>
    </div>
</div>
