@extends('layouts.admin.base')

@section('content')

<main id="content" class="page-body">

    <div class="container-xl">

        {{-- Cabeçalho --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">

                <div class="col">

                    <div class="mb-2">
                        <a
                            href="{{ route('admin.propriedades.index') }}"
                            class="text-secondary text-decoration-none">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-1 me-1">
                                <path d="M15 6l-6 6l6 6"></path>
                            </svg>

                            Propriedades
                        </a>
                    </div>

                    <h2 class="page-title">
                        {{ $propriedade->nome }}
                    </h2>

                    <div class="text-secondary mt-1">
                        {{ $propriedade->localizacao }}
                    </div>

                </div>

                <div class="col-auto ms-auto">
                    <div class="d-flex gap-2">

                        <a
                            href="#"
                            class="btn">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon">
                                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                <path d="M13.5 6.5l4 4"></path>
                            </svg>

                            Editar
                        </a>

                    </div>
                </div>

            </div>
        </div>


        {{-- Resumo --}}
        <div class="row row-deck row-cards mb-4">

            {{-- Área --}}
            <div class="col-sm-6 col-lg-3">

                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <span class="avatar bg-green-lt me-3">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon">
                                    <path d="M3 21l18 0"></path>
                                    <path d="M5 21v-14l8 -4l6 3v15"></path>
                                </svg>
                            </span>

                            <div>
                                <div class="text-secondary">
                                    Área cadastrada
                                </div>

                                <div class="h2 mb-0">
                                    —
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>


            {{-- Talhões --}}
            <div class="col-sm-6 col-lg-3">

                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <span class="avatar bg-blue-lt me-3">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon">
                                    <path d="M3 3l18 18"></path>
                                    <path d="M3 21l18 -18"></path>
                                </svg>
                            </span>

                            <div>
                                <div class="text-secondary">
                                    Talhões
                                </div>

                                <div class="h2 mb-0">
                                    {{ $propriedade->talhoes->count() }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>


            {{-- Usuários --}}
            <div class="col-sm-6 col-lg-3">

                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <span class="avatar bg-purple-lt me-3">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon">
                                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0"></path>
                                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2"></path>
                                </svg>
                            </span>

                            <div>
                                <div class="text-secondary">
                                    Usuários
                                </div>

                                <div class="h2 mb-0">
                                    {{ $propriedade->usuarios->count() }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>


            {{-- Status --}}
            <div class="col-sm-6 col-lg-3">

                <div class="card">
                    <div class="card-body">

                        <div class="d-flex align-items-center">

                            <span class="avatar bg-green-lt me-3">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    class="icon">
                                    <path d="M5 12l5 5l10 -10"></path>
                                </svg>
                            </span>

                            <div>
                                <div class="text-secondary">
                                    Status
                                </div>

                                <div class="mt-1">
                                    <span class="badge bg-green-lt">
                                        Ativa
                                    </span>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>

            </div>

        </div>


        {{-- Conteúdo --}}
        <div class="row row-cards">

            {{-- Informações --}}
            <div class="col-lg-5">

                <div class="card">

                    <div class="card-header">
                        <h3 class="card-title">
                            Informações da propriedade
                        </h3>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <div class="text-secondary">
                                Nome
                            </div>

                            <div class="fw-semibold">
                                {{ $propriedade->nome }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-secondary">
                                Localização
                            </div>

                            <div class="fw-semibold">
                                {{ $propriedade->localizacao }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <div class="text-secondary">
                                Latitude
                            </div>

                            <div class="font-monospace">
                                {{ $propriedade->latitude }}
                            </div>

                        </div>


                        <div>

                            <div class="text-secondary">
                                Longitude
                            </div>

                            <div class="font-monospace">
                                {{ $propriedade->longitude }}
                            </div>

                        </div>

                    </div>

                </div>


                {{-- Usuários vinculados --}}
                <div class="card mt-4">

                    <div class="card-header">

                        <h3 class="card-title">
                            Usuários vinculados
                        </h3>

                    </div>

                    <div class="list-group list-group-flush">

                        @forelse ($propriedade->usuarios as $usuario)

                        <div class="list-group-item">

                            <div class="row align-items-center">

                                <div class="col-auto">
                                    <span class="avatar">
                                        {{ strtoupper(substr($usuario->name, 0, 1)) }}
                                    </span>
                                </div>

                                <div class="col">

                                    <div class="fw-semibold">
                                        {{ $usuario->name }}
                                    </div>

                                    <div class="text-secondary small">
                                        {{ $usuario->email }}
                                    </div>

                                </div>

                                <div class="col-auto">

                                    <span class="badge bg-blue-lt">
                                        {{ ucfirst($usuario->pivot->papel) }}
                                    </span>

                                </div>

                            </div>

                        </div>

                        @empty

                        <div class="card-body text-secondary">
                            Nenhum usuário vinculado.
                        </div>

                        @endforelse

                    </div>

                </div>

            </div>


            {{-- Mapa --}}
            <div class="col-lg-7">

                <div class="card">

                    <div class="card-header">

                        <div>
                            <h3 class="card-title">
                                Localização
                            </h3>

                            <div class="card-subtitle">
                                Localização da propriedade no mapa
                            </div>
                        </div>

                    </div>

                    <div class="card-body p-0">

                        <div
                            id="map-propriedade"
                            style="
                                height: 500px;
                                width: 100%;
                                border-radius: 0 0 8px 8px;
                                overflow: hidden;
                            "></div>

                    </div>

                </div>

            </div>

        </div>


        {{-- Talhões --}}
        <div class="card mt-4">

            <div class="card-header">

                <div>
                    <h3 class="card-title">
                        Talhões
                    </h3>

                    <div class="card-subtitle">
                        Talhões cadastrados nesta propriedade
                    </div>
                </div>

                <div class="card-actions">

                    <a
                        href="{{ route('admin.propriedades.talhoes.create', $propriedade) }}"
                        class="btn btn-primary">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon">
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

                                <td>
                                    <span class="fw-semibold">
                                        {{ $talhao->nome }}
                                    </span>
                                </td>

                                <td>
                                    {{ $talhao->cultura?->nome ?? '—' }}
                                </td>

                                <td>
                                    {{ $talhao->area }} ha
                                </td>

                                <td>
                                    <a href="#" class="btn btn-sm">
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
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="128"
                            height="128"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon text-secondary">
                            <path d="M3 21l18 0"></path>
                            <path d="M5 21v-14l8 -4l6 3v15"></path>
                            <path d="M9 21v-8h4v8"></path>
                        </svg>
                    </div>

                    <p class="empty-title">
                        Nenhum talhão cadastrado
                    </p>

                    <p class="empty-subtitle text-secondary">
                        Os talhões desta propriedade serão cadastrados aqui.
                    </p>

                    <div class="empty-action">

                        <a href="#" class="btn btn-primary">
                            Novo talhão
                        </a>

                    </div>

                </div>

                @endif

            </div>

        </div>

    </div>

</main>


<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const latitude = @js((float) $propriedade->latitude);
        const longitude = @js((float) $propriedade->longitude);

        const mapa = L.map('map-propriedade').setView(
            [latitude, longitude],
            16
        );

        // Satélite
        L.tileLayer(
            'https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            {
                attribution: 'Imagery © Esri',
                maxZoom: 19,
            }
        ).addTo(mapa);

        // Relevo
        L.tileLayer(
            'https://services.arcgisonline.com/ArcGIS/rest/services/Elevation/World_Hillshade/MapServer/tile/{z}/{y}/{x}',
            {
                opacity: 0.35,
                attribution: 'Hillshade © Esri',
                maxZoom: 19,
            }
        ).addTo(mapa);

        // Nomes de cidades e locais
        L.tileLayer(
            'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}',
            {
                attribution: 'Labels © Esri',
                maxZoom: 19,
            }
        ).addTo(mapa);

        // Marcador da propriedade
        L.marker([latitude, longitude])
            .addTo(mapa)
            .bindPopup(
                `<strong>{{ addslashes($propriedade->nome) }}</strong><br>{{ addslashes($propriedade->localizacao) }}`
            )
            .openPopup();

    });
</script>

@endsection