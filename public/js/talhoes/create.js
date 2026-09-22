import { showSuccess, showError } from '../core/toast.js';

const form = document.getElementById('form-criar-talhao');

if (!form) {
    throw new Error('Formulário de criação de talhão não encontrado.');
}

const mapElement = document.getElementById('map');

const propriedadeLat = parseFloat(mapElement.dataset.lat);
const propriedadeLng = parseFloat(mapElement.dataset.lng);

const inputNome = document.getElementById('input-nome');
const inputCultura = document.getElementById('input-cultura');
const inputArea = document.getElementById('input-area');
const inputLimite = document.getElementById('input-limite');

const btnSalvar = document.getElementById('btn-salvar-talhao');
const hintSalvar = document.getElementById('hint-salvar');

const previewNome = document.getElementById('preview-nome');
const previewCultura = document.getElementById('preview-cultura');
const previewArea = document.getElementById('preview-area');
const previewAreaMapa = document.getElementById('preview-area-mapa');

const areaCalculada = document.getElementById('area-calculada');

const badgeStatusMapa = document.getElementById('badge-status-mapa');

const errorNome = document.getElementById('error-nome');
const errorCultura = document.getElementById('error-cultura_id');
const errorArea = document.getElementById('error-area');
const errorLimite = document.getElementById('error-limite');

let poligonoTalhao = null;


// ==========================================================
// MAPA
// ==========================================================

const mapa = L.map('map').setView(
    [propriedadeLat, propriedadeLng],
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


// Nomes de locais
L.tileLayer(
    'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}',
    {
        attribution: 'Labels © Esri',
        maxZoom: 19,
    }
).addTo(mapa);


// ==========================================================
// MARCADOR DA PROPRIEDADE
// ==========================================================

const marcadorPropriedade = L.marker(
    [propriedadeLat, propriedadeLng],
    {
        pmIgnore: true,
    }
).addTo(mapa);

marcadorPropriedade.bindPopup(
    '<strong>Localização da propriedade</strong>'
);


// ==========================================================
// CONTROLES DO GEOMAN
// ==========================================================

mapa.pm.addControls({
    position: 'topleft',

    drawMarker: false,
    drawCircleMarker: false,
    drawPolyline: false,
    drawRectangle: false,
    drawCircle: false,
    drawText: false,

    drawPolygon: true,

    editMode: true,
    dragMode: false,
    cutPolygon: false,
    removalMode: true,
    rotateMode: false,
});


// ==========================================================
// CONFIGURAÇÃO DO POLÍGONO
// ==========================================================

mapa.pm.setGlobalOptions({
    snappable: true,
    allowSelfIntersection: false,
});


// ==========================================================
// QUANDO O POLÍGONO FOR CRIADO
// ==========================================================

mapa.on('pm:create', function (event) {

    // Se já existir um polígono, remove o anterior.
    if (poligonoTalhao) {
        mapa.removeLayer(poligonoTalhao);
    }

    poligonoTalhao = event.layer;

    atualizarTalhao();
});


// ==========================================================
// QUANDO O POLÍGONO FOR EDITADO
// ==========================================================

mapa.on('pm:edit', function () {

    if (!poligonoTalhao) {
        return;
    }

    atualizarTalhao();
});


// ==========================================================
// QUANDO O POLÍGONO FOR REMOVIDO
// ==========================================================

mapa.on('pm:remove', function (event) {

    if (event.layer !== poligonoTalhao) {
        return;
    }

    poligonoTalhao = null;

    inputLimite.value = '';
    inputArea.value = '';

    previewArea.textContent = '0,00';
    previewAreaMapa.textContent = '0,00';

    areaCalculada.classList.add('d-none');

    badgeStatusMapa.className = 'badge bg-secondary-lt';

    badgeStatusMapa.innerHTML = `
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
            />

            <path d="M9 11a3 3 0 1 0 6 0" />

            <path
                d="M17.657 16.657l-4.244 4.243a2 2 0 0 1 -2.827 0l-4.243 -4.243a8 8 0 1 1 11.314 0z"
            />
        </svg>

        Limite não definido
    `;

    atualizarBotao();
});


// ==========================================================
// ATUALIZAR DADOS DO TALHÃO
// ==========================================================

function atualizarTalhao() {

    if (!poligonoTalhao) {
        return;
    }

    const latLngs = poligonoTalhao.getLatLngs();

    if (!latLngs.length || !latLngs[0].length) {
        return;
    }

    const pontos = latLngs[0];

    // ======================================================
    // LIMITES PARA O BANCO
    // ======================================================

    const limite = pontos.map((ponto) => {
        return [
            ponto.lat,
            ponto.lng,
        ];
    });

    inputLimite.value = JSON.stringify(limite);


    // ======================================================
    // CONVERTER PARA GEOJSON
    // Turf utiliza [longitude, latitude]
    // ======================================================

    const coordenadasGeoJson = pontos.map((ponto) => {
        return [
            ponto.lng,
            ponto.lat,
        ];
    });


    // Fecha o polígono
    coordenadasGeoJson.push([
        pontos[0].lng,
        pontos[0].lat,
    ]);


    const polygon = turf.polygon([
        coordenadasGeoJson
    ]);


    // ======================================================
    // CALCULAR ÁREA
    // ======================================================

    const areaMetrosQuadrados = turf.area(polygon);

    const areaHectares = areaMetrosQuadrados / 10000;

    const areaFormatada = areaHectares.toFixed(2);

    inputArea.value = areaFormatada;

    previewArea.textContent = formatarNumero(areaHectares);
    previewAreaMapa.textContent = formatarNumero(areaHectares);

    areaCalculada.classList.remove('d-none');


    // ======================================================
    // STATUS
    // ======================================================

    badgeStatusMapa.className = 'badge bg-green-lt';

    badgeStatusMapa.innerHTML = `
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
            <path d="M5 12l5 5l10 -10"></path>
        </svg>

        Limite definido
    `;


    atualizarBotao();
}


// ==========================================================
// ATUALIZAR BOTÃO
// ==========================================================

function atualizarBotao() {

    const possuiNome = inputNome.value.trim() !== '';
    const possuiLimite = inputLimite.value !== '';
    const possuiArea = parseFloat(inputArea.value) > 0;

    const podeSalvar =
        possuiNome &&
        possuiLimite &&
        possuiArea;

    btnSalvar.disabled = !podeSalvar;

    if (podeSalvar) {

        hintSalvar.textContent =
            'Talhão pronto para ser cadastrado.';

    } else {

        hintSalvar.textContent =
            'Informe o nome e desenhe o limite da lavoura no mapa.';
    }
}


// ==========================================================
// PREVIEW DO NOME
// ==========================================================

inputNome.addEventListener('input', function () {

    const nome = this.value.trim();

    previewNome.textContent =
        nome || 'Nome do talhão';

    atualizarBotao();
});


// ==========================================================
// PREVIEW DA CULTURA
// ==========================================================

inputCultura.addEventListener('change', function () {

    const option =
        this.options[this.selectedIndex];

    previewCultura.textContent =
        option && option.value
            ? option.text
            : 'Cultura não definida';
});


// ==========================================================
// FUNÇÃO DE FORMATAÇÃO
// ==========================================================

function formatarNumero(numero) {

    return new Intl.NumberFormat('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(numero);
}


// ==========================================================
// LIMPAR ERROS
// ==========================================================

function limparErros() {

    [
        inputNome,
        inputCultura,
        inputArea,
    ].forEach((input) => {

        input.classList.remove('is-invalid');

    });

    errorNome.textContent = '';
    errorCultura.textContent = '';
    errorArea.textContent = '';
    errorLimite.textContent = '';

    mapElement.classList.remove('border-danger');
}


// ==========================================================
// SUBMIT
// ==========================================================

form.addEventListener('submit', async function (event) {

    event.preventDefault();

    limparErros();

    if (!inputLimite.value) {

        showError(
            'Desenhe o limite da lavoura no mapa antes de salvar.'
        );

        return;
    }


    const formData = new FormData(form);


    btnSalvar.disabled = true;

    const textoOriginal = btnSalvar.innerHTML;

    btnSalvar.innerHTML = `
        <span
            class="spinner-border spinner-border-sm me-2"
            role="status"
            aria-hidden="true"
        ></span>

        Salvando...
    `;


    try {

        const response = await fetch(
            form.action,
            {
                method: 'POST',

                headers: {
                    'X-CSRF-TOKEN': document.querySelector(
                        'meta[name="csrf-token"]'
                    )?.getAttribute('content') || '',

                    'Accept': 'application/json',
                },

                body: formData,
            }
        );


        const data = await response.json();


        if (!response.ok) {

            if (response.status === 422) {

                mostrarErrosValidacao(data.errors || {});

                showError(
                    'Verifique os dados informados.'
                );

                return;
            }


            throw new Error(
                data.message ||
                'Não foi possível criar o talhão.'
            );
        }


        showSuccess(
            data.message ||
            'Talhão criado com sucesso.'
        );


        setTimeout(() => {

            window.location.href =
                "{{ route('admin.propriedades.talhoes.index', $propriedade) }}";

        }, 700);


    } catch (error) {

        console.error(error);

        showError(
            error.message ||
            'Ocorreu um erro ao criar o talhão.'
        );


    } finally {

        btnSalvar.innerHTML = textoOriginal;

        atualizarBotao();
    }

});


// ==========================================================
// ERROS DE VALIDAÇÃO
// ==========================================================

function mostrarErrosValidacao(errors) {

    if (errors.nome) {

        inputNome.classList.add('is-invalid');

        errorNome.textContent =
            errors.nome[0];
    }


    if (errors.cultura_id) {

        inputCultura.classList.add('is-invalid');

        errorCultura.textContent =
            errors.cultura_id[0];
    }


    if (errors.area) {

        inputArea.classList.add('is-invalid');

        errorArea.textContent =
            errors.area[0];
    }


    if (errors.limite) {

        mapElement.classList.add('border-danger');

        errorLimite.textContent =
            errors.limite[0];

        showError(
            errors.limite[0]
        );
    }
}


// ==========================================================
// ESTADO INICIAL
// ==========================================================

atualizarBotao();