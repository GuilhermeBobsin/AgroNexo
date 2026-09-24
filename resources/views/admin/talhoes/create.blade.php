@extends('layouts.admin.base')

@section('content')

<main id="content" class="page-body">

    <div class="container-xl">

        {{-- Cabeçalho --}}
        <div class="page-header d-print-none mb-4">

            <div class="row align-items-center">

                <div class="col">

                    <div class="page-pretitle">
                        {{ $propriedade->nome }}
                    </div>

                    <h2 class="page-title">
                        Novo talhão
                    </h2>

                </div>

                <div class="col-auto">

                    <a href="{{ route('admin.propriedades.show', $propriedade) }}" class="btn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1 me-1"
                        >
                            <path d="M5 12l14 0"></path>
                            <path d="M5 12l6 6"></path>
                            <path d="M5 12l6 -6"></path>
                        </svg>

                        Voltar para propriedade
                    </a>

                </div>

            </div>

        </div>


        <form id="form-criar-talhao" method="POST" action="{{ route('admin.propriedades.talhoes.store', $propriedade) }}" data-redirect-url="{{ route('admin.propriedades.show', $propriedade) }}">

            @csrf

            <input type="hidden" name="limite" id="input-limite">

            <div class="row g-4">

                <div class="col-lg-7">

                    <div class="card">

                        <div class="card-header">

                            <span class="avatar avatar-sm bg-purple-lt me-2">

                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"
                                >
                                    <path d="M4 20v-10a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v10"></path>
                                    <path d="M4 20h16"></path>
                                    <path d="M8 4v4"></path>
                                    <path d="M16 4v4"></path>
                                </svg>

                            </span>

                            <h3 class="card-title">
                                Dados do talhão
                            </h3>

                        </div>


                        <div class="card-body">

                            <div class="mb-3">

                                <label class="form-label">
                                    Nome do talhão
                                </label>

                                <input type="text" name="nome" id="input-nome" class="form-control" placeholder="Ex: Talhão 01" required autofocus
                                >

                                <div class="invalid-feedback" id="error-nome">
                                </div>

                            </div>


                            <div class="row">

                                <div class="col-md-7 mb-3">

                                    <label class="form-label">
                                        Cultura
                                    </label>

                                    <select
                                        name="cultura_id"
                                        id="input-cultura"
                                        class="form-select"
                                    >

                                        <option value="">
                                            Nenhuma cultura selecionada
                                        </option>

                                        @foreach ($culturas as $cultura)

                                            <option value="{{ $cultura->id }}">
                                                {{ $cultura->nome }}
                                            </option>

                                        @endforeach

                                    </select>

                                    <div
                                        class="invalid-feedback"
                                        id="error-cultura_id"
                                    ></div>

                                </div>


                                {{-- Área --}}
                                <div class="col-md-5 mb-3">

                                    <label class="form-label">
                                        Área do talhão
                                    </label>

                                    <div class="input-group">

                                        <input
                                            type="number"
                                            name="area"
                                            id="input-area"
                                            class="form-control"
                                            placeholder="Automática"
                                            min="0"
                                            step="0.01"
                                            readonly
                                            required
                                        >

                                        <span class="input-group-text">
                                            ha
                                        </span>

                                    </div>

                                    <div
                                        class="invalid-feedback"
                                        id="error-area"
                                    ></div>

                                </div>

                            </div>


                            <div class="form-hint mt-n2 mb-3">
                                A área é calculada automaticamente a partir do limite desenhado no mapa.
                            </div>


                            <hr class="my-4">


                            {{-- Mapa --}}
                            <div class="mb-3">

                                <div class="d-flex align-items-center justify-content-between mb-2">

                                    <label class="form-label mb-0">

                                        Limite da lavoura

                                        <span class="text-danger">
                                            *
                                        </span>

                                    </label>

                                </div>


                                <div class="form-hint mb-2">
                                    Use a ferramenta de polígono no mapa e contorne toda a área da lavoura. Você pode editar os pontos depois de desenhar.
                                </div>


                                <div
                                    id="map"
                                    data-lat="{{ $propriedade->latitude }}"
                                    data-lng="{{ $propriedade->longitude }}"
                                    style="
                                        height: 430px;
                                        width: 100%;
                                        border-radius: 8px;
                                        overflow: hidden;
                                        border: 1px solid var(--tblr-border-color);
                                    "
                                ></div>


                                <div
                                    class="invalid-feedback"
                                    id="error-limite"
                                ></div>

                            </div>


                            {{-- Área calculada --}}
                            <div
                                id="area-calculada"
                                class="alert alert-success py-2 d-none"
                            >

                                <div class="d-flex align-items-center">

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="20"
                                        height="20"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon alert-icon me-2"
                                    >
                                        <path d="M5 12l5 5l10 -10"></path>
                                    </svg>

                                    <div>
                                        Limite definido — área calculada:

                                        <strong>
                                            <span id="preview-area-mapa">
                                                0,00
                                            </span>
                                            ha
                                        </strong>
                                    </div>

                                </div>

                            </div>


                            {{-- Rodapé --}}
                            <div class="form-footer">

                                <div class="d-flex gap-2">

                                    <a
                                        href="{{ route('admin.propriedades.show', $propriedade) }}"
                                        class="btn w-100"
                                    >
                                        Cancelar
                                    </a>


                                    <button
                                        type="submit"
                                        id="btn-salvar-talhao"
                                        class="btn btn-primary w-100"
                                        disabled
                                    >

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
                                            class="icon icon-1 me-1"
                                        >
                                            <path d="M12 5l0 14"></path>
                                            <path d="M5 12l14 0"></path>
                                        </svg>

                                        Criar talhão

                                    </button>

                                </div>


                                <div
                                    class="form-hint text-center mt-2"
                                    id="hint-salvar"
                                >
                                    Desenhe o limite da lavoura no mapa para habilitar o cadastro.
                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Coluna direita --}}
                <div class="col-lg-5">

                    <div class="text-secondary mb-2 small">
                        Pré-visualização
                    </div>


                    <div class="card">

                        <div class="card-header">

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
                                    class="icon"
                                >
                                    <path d="M4 20v-10a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v10"></path>
                                    <path d="M4 20h16"></path>
                                    <path d="M8 4v4"></path>
                                    <path d="M16 4v4"></path>
                                </svg>

                            </span>


                            <div>

                                <h3
                                    class="card-title"
                                    id="preview-nome"
                                >
                                    Nome do talhão
                                </h3>

                                <div
                                    class="card-subtitle"
                                    id="preview-cultura"
                                >
                                    Cultura não definida
                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="mb-3">

                                <div class="text-secondary">
                                    Propriedade
                                </div>

                                <div class="fw-bold">
                                    {{ $propriedade->nome }}
                                </div>

                            </div>


                            <div class="row align-items-center mb-3">

                                <div class="col">

                                    <div class="text-secondary">
                                        Área
                                    </div>

                                    <div class="h2 mb-0">

                                        <span id="preview-area">
                                            0,00
                                        </span>

                                        <small class="text-secondary fs-4">
                                            ha
                                        </small>

                                    </div>

                                </div>

                            </div>


                            <div
                                class="d-flex align-items-center gap-2"
                                id="status-mapa"
                            >

                                <span
                                    id="badge-status-mapa"
                                    class="badge bg-secondary-lt"
                                >

                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        class="icon icon-sm me-1"
                                    >

                                        <path
                                            stroke="none"
                                            d="M0 0h24v24H0z"
                                            fill="none"
                                        />

                                        <path
                                            d="M9 11a3 3 0 1 0 6 0"
                                        />

                                        <path
                                            d="M17.657 16.657l-4.244 4.243a2 2 0 0 1 -2.827 0l-4.243 -4.243a8 8 0 1 1 11.314 0z"
                                        />

                                    </svg>

                                    Limite não definido

                                </span>

                            </div>

                        </div>

                    </div>


                    <div class="text-secondary small mt-2">

                        O talhão será vinculado à propriedade

                        <strong>
                            {{ $propriedade->nome }}
                        </strong>.

                    </div>

                </div>

            </div>

        </form>

    </div>

</main>


{{-- Leaflet --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>

<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
></script>


{{-- Leaflet-Geoman --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.css"
/>

<script
    src="https://unpkg.com/@geoman-io/leaflet-geoman-free@latest/dist/leaflet-geoman.js"
></script>


{{-- Turf --}}
<script
    src="https://cdn.jsdelivr.net/npm/@turf/turf@7/turf.min.js"
></script>


{{-- JS da página --}}
<script
    type="module"
    src="{{ asset('js/talhoes/create.js') }}"
></script>

@endsection