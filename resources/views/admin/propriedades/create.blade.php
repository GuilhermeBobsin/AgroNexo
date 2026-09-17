@extends('layouts.admin.base')
@section('content')

<main id="content" class="page-body">
    <div class="container-xl">

        <div class="page-header d-print-none mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <h2 class="page-title">Nova propriedade</h2>
                    <div class="text-secondary mt-1">Cadastre uma fazenda, sítio ou estância no AgroNexo.</div>
                </div>
            </div>
        </div>

        <form id="form-criar-propriedade" method="POST" data-ajax-form>
            @csrf
            <div class="row g-4">

                {{-- Coluna esquerda: formulário --}}
                <div class="col-lg-7">
                    <div class="card">
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Nome da propriedade</label>
                                {{-- name="" a definir conforme a Model --}}
                                <input type="text" id="input-nome" class="form-control"
                                    placeholder="Ex: Fazenda São João" required autofocus>
                                <div class="invalid-feedback" id="error-nome"></div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Localização</label>
                                <input type="text" id="input-localizacao" class="form-control"
                                    placeholder="Ex: Maquiné, RS">
                                <div class="invalid-feedback" id="error-localizacao"></div>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0">Coordenadas</label>
                                    <button type="button" id="btn-usar-localizacao" class="btn btn-link btn-sm p-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1 me-1">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M12 12m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                            <path d="M12 3l0 3" />
                                            <path d="M12 18l0 3" />
                                            <path d="M3 12l3 0" />
                                            <path d="M18 12l3 0" />
                                        </svg>
                                        Usar minha localização
                                    </button>
                                </div>
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="text" id="input-latitude" class="form-control"
                                            placeholder="Latitude">
                                    </div>
                                    <div class="col">
                                        <input type="text" id="input-longitude" class="form-control"
                                            placeholder="Longitude">
                                    </div>
                                </div>
                                <div class="form-hint mt-1">Opcional — pode preencher depois, ao cadastrar os talhões.</div>
                            </div>

                            <div class="form-footer">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.propriedades.index') }}" class="btn w-100">Cancelar</a>
                                    <button type="submit" id="btn-salvar-propriedade" class="btn btn-primary w-100">
                                        Criar propriedade
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- Coluna direita: pré-visualização, no mesmo estilo do card da listagem --}}
                <div class="col-lg-5">
                    <div class="text-secondary mb-2 small">Pré-visualização</div>

                    <div class="card">
                        <div class="card-header">
                            <span class="avatar bg-green-lt me-3">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon">
                                    <path d="M3 21l18 0"></path>
                                    <path d="M5 21v-14l8 -4l6 3v15"></path>
                                    <path d="M9 21v-8h4v8"></path>
                                </svg>
                            </span>
                            <div>
                                <h3 class="card-title" id="preview-nome">Nome da propriedade</h3>
                                <div class="card-subtitle" id="preview-localizacao">Localização</div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row align-items-center mb-3">
                                <div class="col">
                                    <div class="text-secondary">Talhões</div>
                                    <div class="h2 mb-0">0</div>
                                </div>
                                <div class="col-auto">
                                    <span class="badge bg-green-lt">Ativa</span>
                                </div>
                            </div>
                            <div class="text-secondary small">
                                Os talhões, culturas e aplicações são cadastrados depois, dentro da propriedade.
                            </div>
                        </div>
                    </div>

                    <div class="text-secondary small mt-2">
                        É assim que a propriedade vai aparecer na listagem depois de criada.
                    </div>
                </div>

            </div>
        </form>

    </div>
</main>

<script type="module" src="{{ asset('js/propriedades/create.js') }}"></script>

@endsection