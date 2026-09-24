@extends('layouts.admin.base')

@section('content')

<main id="content" class="page-body">

    <div class="container-xl">
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">

                <div class="col">

                    <div class="mb-2">
                        <a href="{{ route('admin.propriedades.index') }}" class="text-secondary text-decoration-none">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1 me-1">
                                <path d="M15 6l-6 6l6 6"></path>
                            </svg>
                            Propriedades
                        </a>
                    </div>

                    <h2 class="page-title">{{ $propriedade->nome }}</h2>
                    <div class="text-secondary mt-1">{{ $propriedade->localizacao }}</div>

                </div>

                <div class="col-auto ms-auto">
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.propriedades.edit', $propriedade) }}" class="btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                <path d="M13.5 6.5l4 4"></path>
                            </svg>
                            Editar
                        </a>
                    </div>
                    <form method="POST" action="{{ route('admin.propriedades.destroy', $propriedade) }}" onsubmit="return confirm('Excluir esta propriedade? Registros vinculados precisam ser removidos antes.')">@csrf @method('DELETE')<button class="btn btn-outline-danger">Excluir propriedade</button></form>
                </div>

            </div>
        </div>

        <div class="row row-deck row-cards mb-4">

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-green-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M3 21l18 0"></path>
                                    <path d="M5 21v-14l8 -4l6 3v15"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Área cadastrada</div>
                                <div class="h2 mb-0">{{ number_format($propriedade->talhoes->sum('area'), 1, ',', '.') }} ha</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-purple-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M4 20v-10a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v10"></path>
                                    <path d="M4 20h16"></path>
                                    <path d="M8 4v4"></path>
                                    <path d="M16 4v4"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Talhões</div>
                                <div class="h2 mb-0">{{ $propriedade->talhoes->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-blue-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Usuários</div>
                                <div class="h2 mb-0">{{ $propriedade->usuarios->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <span class="avatar bg-green-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                            </span>
                            <div>
                                <div class="text-secondary">Status</div>
                                <div class="mt-1">
                                    <span class="badge bg-green-lt">Ativa</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row row-cards">


            <div class="col-lg-5">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informações da propriedade</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="text-secondary">Nome</div>
                            <div class="fw-semibold">{{ $propriedade->nome }}</div>
                        </div>
                        <div>
                            <div class="text-secondary">Localização</div>
                            <div class="fw-semibold">{{ $propriedade->localizacao }}</div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">Usuários vinculados</h3>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse ($propriedade->usuarios as $usuario)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="avatar">{{ strtoupper(substr($usuario->name, 0, 1)) }}</span>
                                    </div>
                                    <div class="col">
                                        <div class="fw-semibold">{{ $usuario->name }}</div>
                                        <div class="text-secondary small">{{ $usuario->email }}</div>
                                    </div>
                                    <div class="col-auto">
                                        <span class="badge bg-blue-lt">{{ ucfirst($usuario->pivot->papel) }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="card-body text-secondary">Nenhum usuário vinculado.</div>
                        @endforelse
                    </div>
                </div>

            </div>

            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class="card-title">Localização e talhões</h3>
                            <div class="card-subtitle">Talhões desenhados aparecem coloridos por cultura</div>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div id="map-propriedade" style="height: 500px; width: 100%; border-radius: 0 0 8px 8px; overflow: hidden;"></div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card mt-4">

            <div class="card-header">
                <div>
                    <h3 class="card-title">Talhões</h3>
                    <div class="card-subtitle">Talhões cadastrados nesta propriedade</div>
                </div>

                <div class="card-actions">
                    <a href="{{ route('admin.propriedades.talhoes.create', $propriedade) }}" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                            <path d="M12 5l0 14"></path>
                            <path d="M5 12l14 0"></path>
                        </svg>
                        Novo talhão
                    </a>
                </div>
            </div>

            <div class="card-body">
                @if ($propriedade->talhoes->count())
                    <div class="table-responsive">
                        <table class="table table-vcenter">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>Cultura</th>
                                    <th>Área</th>
                                    <th class="w-1"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($propriedade->talhoes as $talhao)
                                    <tr>
                                        <td><span class="fw-semibold">{{ $talhao->nome }}</span></td>
                                        <td>{{ $talhao->cultura?->nome ?? '—' }}</td>
                                        <td>{{ number_format($talhao->area, 1, ',', '.') }} ha</td>
                                        <td>
                                            <a href="{{ route('admin.propriedades.talhoes.show', [$propriedade, $talhao]) }}" class="btn btn-sm">
                                                Visualizar
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty">
                        <div class="empty-img">
                            <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" class="icon text-secondary">
                                <path d="M3 21l18 0"></path>
                                <path d="M5 21v-14l8 -4l6 3v15"></path>
                                <path d="M9 21v-8h4v8"></path>
                            </svg>
                        </div>
                        <p class="empty-title">Nenhum talhão cadastrado</p>
                        <p class="empty-subtitle text-secondary">Os talhões desta propriedade serão cadastrados aqui.</p>
                        <div class="empty-action">
                            <a href="{{ route('admin.propriedades.talhoes.create', $propriedade) }}" class="btn btn-primary">Novo talhão</a>
                        </div>
                    </div>
                @endif
            </div>

        </div>

    </div>

</main>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const propriedadeLat = @js($propriedade->latitude ? (float) $propriedade->latitude : null);
    const propriedadeLng = @js($propriedade->longitude ? (float) $propriedade->longitude : null);

    const talhoes = @js($propriedade->talhoes->map(fn ($t) => [
        'nome' => $t->nome,
        'cultura' => $t->cultura?->nome,
        'area' => $t->area,
        'limite' => $t->limite,
    ]));

    function corParaCultura(nome) {
        if (!nome) return '#868e96';
        let hash = 0;
        for (let i = 0; i < nome.length; i++) {
            hash = nome.charCodeAt(i) + ((hash << 5) - hash);
        }
        const hue = Math.abs(hash) % 360;
        return `hsl(${hue}, 65%, 45%)`;
    }

    // 1. DESATIVA OS CRÉDITOS AQUI (attributionControl: false)
    const mapa = L.map('map-propriedade', {
        attributionControl: false
    });

    // 2. CRÉDITOS (attribution) REMOVIDOS DOS TILE LAYERS
    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
    }).addTo(mapa);

    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Elevation/World_Hillshade/MapServer/tile/{z}/{y}/{x}', {
        opacity: 0.35, maxZoom: 19,
    }).addTo(mapa);

    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 19,
    }).addTo(mapa);

    const grupoTalhoes = L.featureGroup().addTo(mapa);

    talhoes.forEach((talhao) => {
        if (!talhao.limite || talhao.limite.length < 3) return;

        const cor = corParaCultura(talhao.cultura);

        L.polygon(talhao.limite, {
            color: cor,
            fillColor: cor,
            fillOpacity: 0.25,
            weight: 2,
        })
            .addTo(grupoTalhoes)
            .bindPopup(`
                <strong>${talhao.nome}</strong><br>
                ${talhao.cultura ?? 'Sem cultura'} · ${Number(talhao.area).toLocaleString('pt-BR', { minimumFractionDigits: 1 })} ha
            `);
    });

    if (grupoTalhoes.getLayers().length > 0) {
        const bounds = grupoTalhoes.getBounds();
        if (propriedadeLat && propriedadeLng) {
            bounds.extend([propriedadeLat, propriedadeLng]);
        }
        mapa.fitBounds(bounds, { padding: [30, 30] });
    } else if (propriedadeLat && propriedadeLng) {
        mapa.setView([propriedadeLat, propriedadeLng], 14);
        L.marker([propriedadeLat, propriedadeLng])
            .addTo(mapa)
            .bindPopup(`<strong>{{ addslashes($propriedade->nome) }}</strong><br>{{ addslashes($propriedade->localizacao) }}`)
            .openPopup();
    } else {
        mapa.setView([-14.235, -51.925], 4);
    }
});
</script>

@endsection
