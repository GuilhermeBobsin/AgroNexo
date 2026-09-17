document.addEventListener('DOMContentLoaded', () => {

    /*
    |--------------------------------------------------------------------------
    | Elementos do formulário
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById('form-criar-propriedade');

    const inputNome = document.getElementById('input-nome');
    const inputLocalizacao = document.getElementById('input-localizacao');

    const inputLatitude = document.getElementById('input-latitude');
    const inputLongitude = document.getElementById('input-longitude');

    const btnLocalizacao = document.getElementById(
        'btn-usar-localizacao'
    );

    const btnSalvar = document.getElementById(
        'btn-salvar-propriedade'
    );


    /*
    |--------------------------------------------------------------------------
    | Pré-visualização
    |--------------------------------------------------------------------------
    */

    const previewNome = document.getElementById(
        'preview-nome'
    );

    const previewLocalizacao = document.getElementById(
        'preview-localizacao'
    );

    const previewLatitude = document.getElementById(
        'preview-latitude'
    );

    const previewLongitude = document.getElementById(
        'preview-longitude'
    );

    const previewLatitudeCard = document.getElementById(
        'preview-latitude-card'
    );

    const previewLongitudeCard = document.getElementById(
        'preview-longitude-card'
    );

    const coordenadasSelecionadas = document.getElementById(
        'coordenadas-selecionadas'
    );


    /*
    |--------------------------------------------------------------------------
    | Verificar se o mapa existe
    |--------------------------------------------------------------------------
    */

    const mapElement = document.getElementById('map');

    if (!mapElement) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Atualizar pré-visualização
    |--------------------------------------------------------------------------
    */

    function atualizarPreview() {

        if (previewNome) {
            previewNome.textContent =
                inputNome?.value.trim() ||
                'Nome da propriedade';
        }

        if (previewLocalizacao) {
            previewLocalizacao.textContent =
                inputLocalizacao?.value.trim() ||
                'Localização';
        }

    }


    inputNome?.addEventListener(
        'input',
        atualizarPreview
    );

    inputLocalizacao?.addEventListener(
        'input',
        atualizarPreview
    );


    /*
    |--------------------------------------------------------------------------
    | Inicialização do mapa
    |--------------------------------------------------------------------------
    |
    | Coordenadas iniciais aproximadas do litoral norte do RS.
    | O usuário poderá alterar clicando no mapa.
    |
    */

    const mapa = L.map('map', {
        zoomControl: true
    }).setView(
        [-29.9230, -50.9921],
        10
    );


    /*
    |--------------------------------------------------------------------------
    | Imagem de satélite
    |--------------------------------------------------------------------------
    |
    | World Imagery da Esri.
    |
    */

    const satelite = L.tileLayer(
        'https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
        {
            attribution: 'Imagery © Esri'
        }
    );

    satelite.addTo(mapa);


    /*
    |--------------------------------------------------------------------------
    | Relevo / Hillshade
    |--------------------------------------------------------------------------
    |
    | Fica permanentemente sobre a imagem de satélite.
    |
    */

    const relevo = L.tileLayer(
        'https://services.arcgisonline.com/ArcGIS/rest/services/Elevation/World_Hillshade/MapServer/tile/{z}/{y}/{x}',
        {
            opacity: 0.35,
            attribution: 'Hillshade © Esri'
        }
    );

    relevo.addTo(mapa);


    /*
    |--------------------------------------------------------------------------
    | Nomes de cidades e lugares
    |--------------------------------------------------------------------------
    |
    | Camada de referência sobre a imagem.
    |
    */

    const nomes = L.tileLayer(
        'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}',
        {
            opacity: 1,
            attribution: 'Labels © Esri'
        }
    );

    nomes.addTo(mapa);


    /*
    |--------------------------------------------------------------------------
    | Marcador da propriedade
    |--------------------------------------------------------------------------
    */

    let marcador = null;


    /*
    |--------------------------------------------------------------------------
    | Selecionar localização
    |--------------------------------------------------------------------------
    */

    function selecionarLocalizacao(
        latitude,
        longitude
    ) {

        /*
        |--------------------------------------------------------------------------
        | Salvar latitude e longitude nos inputs hidden
        |--------------------------------------------------------------------------
        */

        inputLatitude.value =
            latitude.toFixed(7);

        inputLongitude.value =
            longitude.toFixed(7);


        /*
        |--------------------------------------------------------------------------
        | Atualizar informações de coordenadas
        |--------------------------------------------------------------------------
        */

        if (previewLatitude) {
            previewLatitude.textContent =
                latitude.toFixed(7);
        }

        if (previewLongitude) {
            previewLongitude.textContent =
                longitude.toFixed(7);
        }

        if (previewLatitudeCard) {
            previewLatitudeCard.textContent =
                latitude.toFixed(7);
        }

        if (previewLongitudeCard) {
            previewLongitudeCard.textContent =
                longitude.toFixed(7);
        }


        /*
        |--------------------------------------------------------------------------
        | Mostrar confirmação
        |--------------------------------------------------------------------------
        */

        if (coordenadasSelecionadas) {
            coordenadasSelecionadas.classList.remove(
                'd-none'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Criar marcador
        |--------------------------------------------------------------------------
        */

        if (!marcador) {

            marcador = L.marker(
                [
                    latitude,
                    longitude
                ],
                {
                    draggable: true
                }
            ).addTo(mapa);


            /*
            |--------------------------------------------------------------------------
            | Arrastar marcador
            |--------------------------------------------------------------------------
            */

            marcador.on(
                'dragend',
                function (event) {

                    const posicao =
                        event.target.getLatLng();

                    selecionarLocalizacao(
                        posicao.lat,
                        posicao.lng
                    );

                }
            );

        } else {

            marcador.setLatLng([
                latitude,
                longitude
            ]);

        }


        /*
        |--------------------------------------------------------------------------
        | Centralizar mapa
        |--------------------------------------------------------------------------
        */

        mapa.setView(
            [
                latitude,
                longitude
            ],
            16
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Clique no mapa
    |--------------------------------------------------------------------------
    */

    mapa.on(
        'click',
        function (event) {

            selecionarLocalizacao(
                event.latlng.lat,
                event.latlng.lng
            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Usar localização atual
    |--------------------------------------------------------------------------
    */

    btnLocalizacao?.addEventListener(
        'click',
        function () {

            if (!navigator.geolocation) {

                alert(
                    'Seu navegador não permite obter sua localização.'
                );

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Estado do botão
            |--------------------------------------------------------------------------
            */

            const textoOriginal =
                btnLocalizacao.textContent;

            btnLocalizacao.disabled = true;

            btnLocalizacao.textContent =
                'Obtendo localização...';


            /*
            |--------------------------------------------------------------------------
            | Geolocalização
            |--------------------------------------------------------------------------
            */

            navigator.geolocation.getCurrentPosition(

                function (position) {

                    selecionarLocalizacao(
                        position.coords.latitude,
                        position.coords.longitude
                    );


                    btnLocalizacao.disabled = false;

                    btnLocalizacao.textContent =
                        textoOriginal;

                },


                function (error) {

                    console.error(
                        'Erro ao obter localização:',
                        error
                    );


                    btnLocalizacao.disabled = false;

                    btnLocalizacao.textContent =
                        textoOriginal;


                    alert(
                        'Não foi possível obter sua localização. Verifique as permissões do navegador.'
                    );

                },


                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }

            );

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Envio do formulário via AJAX
    |--------------------------------------------------------------------------
    */

    form?.addEventListener(
        'submit',
        async function (event) {

            event.preventDefault();


            /*
            |--------------------------------------------------------------------------
            | Verificar localização
            |--------------------------------------------------------------------------
            */

            if (
                !inputLatitude.value ||
                !inputLongitude.value
            ) {

                if (
                    typeof window.showToast ===
                    'function'
                ) {

                    window.showToast(
                        'Localização',
                        'Selecione a localização da propriedade no mapa.',
                        'warning'
                    );

                } else {

                    alert(
                        'Selecione a localização da propriedade no mapa.'
                    );

                }

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Estado do botão
            |--------------------------------------------------------------------------
            */

            const textoOriginal =
                btnSalvar.textContent;

            btnSalvar.disabled = true;

            btnSalvar.textContent =
                'Criando...';


            /*
            |--------------------------------------------------------------------------
            | FormData
            |--------------------------------------------------------------------------
            */

            const formData =
                new FormData(form);


            try {

                /*
                |--------------------------------------------------------------------------
                | Requisição
                |--------------------------------------------------------------------------
                */

                const response =
                    await fetch(
                        form.action ||
                        "{{ route('admin.propriedades.store') }}",
                        {
                            method: 'POST',

                            headers: {

                                'X-CSRF-TOKEN':
                                    document
                                        .querySelector(
                                            'meta[name="csrf-token"]'
                                        )
                                        ?.getAttribute(
                                            'content'
                                        ),

                                'Accept':
                                    'application/json'
                            },

                            body: formData
                        }
                    );


                /*
                |--------------------------------------------------------------------------
                | Resposta
                |--------------------------------------------------------------------------
                */

                const data =
                    await response.json();


                /*
                |--------------------------------------------------------------------------
                | Erros de validação
                |--------------------------------------------------------------------------
                */

                if (
                    response.status === 422
                ) {

                    console.error(
                        'Erros de validação:',
                        data.errors
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Nome
                    |--------------------------------------------------------------------------
                    */

                    const errorNome =
                        document.getElementById(
                            'error-nome'
                        );

                    if (errorNome) {
                        errorNome.textContent =
                            data.errors?.nome?.[0] || '';

                        inputNome.classList.toggle(
                            'is-invalid',
                            !!data.errors?.nome
                        );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Localização
                    |--------------------------------------------------------------------------
                    */

                    const errorLocalizacao =
                        document.getElementById(
                            'error-localizacao'
                        );

                    if (errorLocalizacao) {
                        errorLocalizacao.textContent =
                            data.errors?.localizacao?.[0] || '';

                        inputLocalizacao.classList.toggle(
                            'is-invalid',
                            !!data.errors?.localizacao
                        );
                    }


                    if (
                        typeof window.showToast ===
                        'function'
                    ) {

                        window.showToast(
                            'Erro de validação',
                            'Verifique os campos preenchidos.',
                            'danger'
                        );

                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Outros erros
                |--------------------------------------------------------------------------
                */

                if (!response.ok) {

                    throw new Error(
                        data.message ||
                        'Erro ao criar propriedade.'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Sucesso
                |--------------------------------------------------------------------------
                */

                console.log(
                    'Propriedade criada:',
                    data
                );


                if (
                    typeof window.showToast ===
                    'function'
                ) {

                    window.showToast(
                        'Sucesso',
                        data.message ||
                        'Propriedade criada com sucesso!',
                        'success'
                    );

                } else {

                    alert(
                        data.message ||
                        'Propriedade criada com sucesso!'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Limpar formulário
                |--------------------------------------------------------------------------
                */

                form.reset();

                inputLatitude.value = '';
                inputLongitude.value = '';


                /*
                |--------------------------------------------------------------------------
                | Limpar coordenadas
                |--------------------------------------------------------------------------
                */

                if (coordenadasSelecionadas) {

                    coordenadasSelecionadas.classList.add(
                        'd-none'
                    );

                }


                /*
                |--------------------------------------------------------------------------
                | Remover marcador
                |--------------------------------------------------------------------------
                */

                if (marcador) {

                    mapa.removeLayer(
                        marcador
                    );

                    marcador = null;

                }


                /*
                |--------------------------------------------------------------------------
                | Resetar preview
                |--------------------------------------------------------------------------
                */

                if (previewLatitude) {
                    previewLatitude.textContent = '—';
                }

                if (previewLongitude) {
                    previewLongitude.textContent = '—';
                }

                if (previewLatitudeCard) {
                    previewLatitudeCard.textContent = '—';
                }

                if (previewLongitudeCard) {
                    previewLongitudeCard.textContent = '—';
                }

                atualizarPreview();

            } catch (error) {

                console.error(
                    'Erro ao criar propriedade:',
                    error
                );


                if (
                    typeof window.showToast ===
                    'function'
                ) {

                    window.showToast(
                        'Erro',
                        error.message ||
                        'Não foi possível criar a propriedade.',
                        'danger'
                    );

                } else {

                    alert(
                        error.message ||
                        'Não foi possível criar a propriedade.'
                    );

                }

            } finally {

                btnSalvar.disabled = false;

                btnSalvar.textContent =
                    textoOriginal;

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Preview inicial
    |--------------------------------------------------------------------------
    */

    atualizarPreview();

});