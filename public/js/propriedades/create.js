document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-criar-propriedade');
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    const inputNome = document.getElementById('input-nome');
    const inputLocalizacao = document.getElementById('input-localizacao');
    const inputLatitude = document.getElementById('input-latitude');
    const inputLongitude = document.getElementById('input-longitude');

    const btnLocalizacao = document.getElementById('btn-usar-localizacao');
    const btnSalvar = document.getElementById('btn-salvar-propriedade');
    const hintSalvar = document.getElementById('hint-salvar');

    const previewNome = document.getElementById('preview-nome');
    const previewLocalizacao = document.getElementById('preview-localizacao');
    const previewLatitude = document.getElementById('preview-latitude');
    const previewLongitude = document.getElementById('preview-longitude');
    const coordenadasSelecionadas = document.getElementById('coordenadas-selecionadas');
    const statusMapa = document.getElementById('status-mapa');

    function atualizarPreview() {
        previewNome.textContent = inputNome.value.trim() || 'Nome da propriedade';
        previewLocalizacao.textContent = inputLocalizacao.value.trim() || 'Localização';
    }

    inputNome.addEventListener('input', atualizarPreview);
    inputLocalizacao.addEventListener('input', atualizarPreview);

    const mapa = L.map('map', { attributionControl: false }).setView([-29.9230, -50.9921], 10);

    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Imagery © Esri',
    }).addTo(mapa);

    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Elevation/World_Hillshade/MapServer/tile/{z}/{y}/{x}', {
        opacity: 0.35,
        attribution: 'Hillshade © Esri',
    }).addTo(mapa);

    L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Labels © Esri',
    }).addTo(mapa);

    let marcador = null;

    function marcarComoDefinida() {
        coordenadasSelecionadas.classList.remove('d-none');
        statusMapa.innerHTML = `
            <span class="badge bg-green-lt">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-sm me-1">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l5 5l10 -10" />
                </svg>
                Localização definida
            </span>
        `;
        btnSalvar.disabled = false;
        hintSalvar.classList.add('d-none');
    }

    function marcarComoIndefinida() {
        coordenadasSelecionadas.classList.add('d-none');
        statusMapa.innerHTML = `<span class="badge bg-secondary-lt">Localização não definida</span>`;
        btnSalvar.disabled = true;
        hintSalvar.classList.remove('d-none');
    }

    function selecionarLocalizacao(latitude, longitude) {
        inputLatitude.value = latitude.toFixed(7);
        inputLongitude.value = longitude.toFixed(7);
        previewLatitude.textContent = latitude.toFixed(7);
        previewLongitude.textContent = longitude.toFixed(7);
        marcarComoDefinida();

        if (!marcador) {
            marcador = L.marker([latitude, longitude], { draggable: true }).addTo(mapa);
            marcador.on('dragend', (e) => {
                const pos = e.target.getLatLng();
                selecionarLocalizacao(pos.lat, pos.lng);
            });
        } else {
            marcador.setLatLng([latitude, longitude]);
        }

        mapa.setView([latitude, longitude], 16);
    }

    mapa.on('click', (e) => selecionarLocalizacao(e.latlng.lat, e.latlng.lng));

    btnLocalizacao.addEventListener('click', async () => {
        if (!navigator.geolocation) {
            const { showError } = await import('../core/toast.js');
            showError('Não suportado', 'Seu navegador não permite obter sua localização.');
            return;
        }

        const textoOriginal = btnLocalizacao.textContent;
        btnLocalizacao.disabled = true;
        btnLocalizacao.textContent = 'Obtendo localização...';

        navigator.geolocation.getCurrentPosition(
            (position) => {
                selecionarLocalizacao(position.coords.latitude, position.coords.longitude);
                btnLocalizacao.disabled = false;
                btnLocalizacao.textContent = textoOriginal;
            },
            async () => {
                btnLocalizacao.disabled = false;
                btnLocalizacao.textContent = textoOriginal;
                const { showError } = await import('../core/toast.js');
                showError('Não foi possível obter', 'Verifique as permissões de localização do navegador.');
            },
            { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
        );
    });

    form.addEventListener('ajax-success', () => {
        inputLatitude.value = '';
        inputLongitude.value = '';
        if (marcador) {
            mapa.removeLayer(marcador);
            marcador = null;
        }
        marcarComoIndefinida();
        atualizarPreview();
    });

    atualizarPreview();
});