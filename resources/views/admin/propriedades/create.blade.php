@extends('layouts.admin.base')

@section('content')

<main id="content" class="page-body">

    <div class="container-xl">

        {{-- Cabeçalho --}}
        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Nova propriedade
                    </h2>

                    <div class="text-secondary mt-1">
                        Cadastre uma fazenda, sítio ou estância no AgroNexo.
                    </div>
                </div>
            </div>
        </div>


        <form
            id="form-criar-propriedade"
            method="POST"
            data-ajax-form
        >
            @csrf

            <div class="row g-4">

                {{-- ==========================================================
                     COLUNA ESQUERDA
                =========================================================== --}}
                <div class="col-lg-7">

                    <div class="card">

                        <div class="card-body">

                            {{-- Nome --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Nome da propriedade
                                </label>

                                <input
                                    type="text"
                                    name="nome"
                                    id="input-nome"
                                    class="form-control"
                                    placeholder="Ex: Fazenda São João"
                                    required
                                    autofocus
                                >

                                <div
                                    class="invalid-feedback"
                                    id="error-nome">
                                </div>

                            </div>


                            {{-- Localização --}}
                            <div class="mb-3">

                                <label class="form-label">
                                    Localização
                                </label>

                                <input
                                    type="text"
                                    name="localizacao"
                                    id="input-localizacao"
                                    class="form-control"
                                    placeholder="Ex: Maquiné, RS"
                                    required
                                >

                                <div
                                    class="form-hint mt-1">
                                    Informe uma referência para a localização da propriedade.
                                </div>

                                <div
                                    class="invalid-feedback"
                                    id="error-localizacao">
                                </div>

                            </div>


                            {{-- Mapa --}}
                            <div class="mb-3">

                                <div class="d-flex align-items-center justify-content-between mb-2">

                                    <label class="form-label mb-0">
                                        Localização no mapa
                                    </label>

                                    <button
                                        type="button"
                                        id="btn-usar-localizacao"
                                        class="btn btn-link btn-sm p-0"
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
                                            <path
                                                stroke="none"
                                                d="M0 0h24v24H0z"
                                                fill="none"
                                            />

                                            <path
                                                d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 0 0 -6 0"
                                            />

                                            <path d="M12 3l0 3" />
                                            <path d="M12 18l0 3" />
                                            <path d="M3 12l3 0" />
                                            <path d="M18 12l3 0" />
                                        </svg>

                                        Usar minha localização

                                    </button>

                                </div>


                                {{-- MAPA --}}
                                <div
                                    id="map"
                                    style="
                                        height: 360px;
                                        width: 100%;
                                        border-radius: 8px;
                                        overflow: hidden;
                                        border: 1px solid var(--tblr-border-color);
                                    "
                                ></div>


                                <div class="form-hint mt-2">
                                    Clique no mapa para definir a localização da propriedade.
                                    Você também pode arrastar o marcador.
                                </div>


                                {{-- Valores técnicos --}}
                                <input
                                    type="hidden"
                                    name="latitude"
                                    id="input-latitude"
                                >

                                <input
                                    type="hidden"
                                    name="longitude"
                                    id="input-longitude"
                                >

                            </div>


                            {{-- Coordenadas selecionadas --}}
                            <div
                                id="coordenadas-selecionadas"
                                class="alert alert-success d-none"
                            >

                                <div class="d-flex">

                                    <div>
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
                                            class="icon alert-icon"
                                        >
                                            <path d="M5 12l5 5l10 -10"></path>
                                        </svg>
                                    </div>

                                    <div>
                                        <h4 class="alert-title mb-1">
                                            Localização selecionada
                                        </h4>

                                        <div class="text-secondary">
                                            Latitude:
                                            <strong id="preview-latitude">-</strong>
                                            <br>

                                            Longitude:
                                            <strong id="preview-longitude">-</strong>
                                        </div>
                                    </div>

                                </div>

                            </div>


                            {{-- Rodapé --}}
                            <div class="form-footer">

                                <div class="d-flex gap-2">

                                    <a
                                        href="{{ route('admin.propriedades.index') }}"
                                        class="btn w-100"
                                    >
                                        Cancelar
                                    </a>

                                    <button
                                        type="submit"
                                        id="btn-salvar-propriedade"
                                        class="btn btn-primary w-100"
                                    >
                                        Criar propriedade
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- ==========================================================
                     COLUNA DIREITA
                =========================================================== --}}
                <div class="col-lg-5">

                    <div class="text-secondary mb-2 small">
                        Pré-visualização
                    </div>


                    <div class="card">

                        <div class="card-header">

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
                                    class="icon"
                                >
                                    <path d="M3 21l18 0"></path>
                                    <path d="M5 21v-14l8 -4l6 3v15"></path>
                                    <path d="M9 21v-8h4v8"></path>
                                </svg>

                            </span>

                            <div>

                                <h3
                                    class="card-title"
                                    id="preview-nome"
                                >
                                    Nome da propriedade
                                </h3>

                                <div
                                    class="card-subtitle"
                                    id="preview-localizacao"
                                >
                                    Localização
                                </div>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row align-items-center mb-3">

                                <div class="col">

                                    <div class="text-secondary">
                                        Talhões
                                    </div>

                                    <div class="h2 mb-0">
                                        0
                                    </div>

                                </div>

                                <div class="col-auto">

                                    <span class="badge bg-green-lt">
                                        Ativa
                                    </span>

                                </div>

                            </div>


                            <div class="hr-text">
                                Localização
                            </div>


                            <div class="text-secondary small">

                                <div class="mb-2">
                                    <strong>Latitude:</strong>
                                    <span id="preview-latitude-card">
                                        —
                                    </span>
                                </div>

                                <div>
                                    <strong>Longitude:</strong>
                                    <span id="preview-longitude-card">
                                        —
                                    </span>
                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="text-secondary small mt-2">
                        É assim que a propriedade poderá ser apresentada
                        na listagem depois de criada.
                    </div>

                </div>

            </div>

        </form>

    </div>

</main>


{{-- Leaflet CSS --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
/>


{{-- Leaflet JS --}}
<script
    src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
></script>


{{-- Seu JS --}}
<script
    type="module"
    src="{{ asset('js/propriedades/create.js') }}"
></script>

@endsection