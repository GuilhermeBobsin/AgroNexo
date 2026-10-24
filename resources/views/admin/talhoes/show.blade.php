@extends('layouts.admin.base')

@section('content')
<main class="page-body">
    <div class="container-xl">
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">{{ $propriedade->nome }}</div>
                    <h2 class="page-title">{{ $talhao->nome }}</h2>
                </div>
                <div class="col-auto d-flex gap-2">
                    <a class="btn" href="{{ route('admin.propriedades.talhoes.index', $propriedade) }}">Todos os talhões</a>
                    <a class="btn btn-primary" href="{{ route('admin.propriedades.talhoes.edit', [$propriedade, $talhao]) }}">Editar talhão</a>
                </div>
            </div>
        </div>

        @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        <div class="row row-cards mb-3">
            <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-secondary">Propriedade</div><div class="h3 mb-0">{{ $propriedade->nome }}</div></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-secondary">Cultura</div><div class="h3 mb-0">{{ $talhao->cultura?->nome ?? 'Não definida' }}</div></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><div class="text-secondary">Área</div><div class="h3 mb-0">{{ number_format((float) $talhao->area, 2, ',', '.') }} ha</div></div></div></div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h3 class="card-title">Limite do talhão</h3></div>
            <div class="card-body p-0"><div id="map-talhao" style="height: 420px" data-limite="{{ json_encode($talhao->limite ?? []) }}"></div></div>
        </div>

        <div class="card">
            <div class="card-header"><h3 class="card-title">Aplicações registradas</h3></div>
            <div class="table-responsive"><table class="table table-vcenter card-table">
                <thead><tr><th>Data</th><th>Produto</th><th>Dose</th><th>Status</th></tr></thead>
                <tbody>
                @forelse ($talhao->aplicacoes as $aplicacao)
                    <tr><td>{{ $aplicacao->data_aplicacao?->format('d/m/Y') ?? '—' }}</td><td>{{ $aplicacao->produto?->nome ?? '—' }}</td><td>{{ $aplicacao->dose ?? '—' }}</td><td>{{ $aplicacao->status ?? '—' }}</td></tr>
                @empty
                    <tr><td colspan="4" class="text-center text-secondary py-4">Nenhuma aplicação registrada.</td></tr>
                @endforelse
                </tbody>
            </table></div>
        </div>
    </div>
</main>
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const mapElement = document.getElementById('map-talhao');
    let limite = [];
    try { limite = JSON.parse(mapElement.dataset.limite || '[]'); } catch (_) {}
    const map = L.map(mapElement).setView([-14.235, -51.925], 4);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
    if (Array.isArray(limite) && limite.length >= 3) {
        const polygon = L.polygon(limite).addTo(map);
        map.fitBounds(polygon.getBounds(), { padding: [24, 24] });
    }
</script>
@endsection
