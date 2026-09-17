document.addEventListener('DOMContentLoaded', () => {
    const inputNome = document.getElementById('input-nome');
    const inputLocalizacao = document.getElementById('input-localizacao');
    const previewNome = document.getElementById('preview-nome');
    const previewLocalizacao = document.getElementById('preview-localizacao');

    inputNome?.addEventListener('input', () => {
        previewNome.textContent = inputNome.value.trim() || 'Nome da propriedade';
    });

    inputLocalizacao?.addEventListener('input', () => {
        previewLocalizacao.textContent = inputLocalizacao.value.trim() || 'Localização';
    });

    const btnLocalizacao = document.getElementById('btn-usar-localizacao');
    const inputLat = document.getElementById('input-latitude');
    const inputLng = document.getElementById('input-longitude');

    btnLocalizacao?.addEventListener('click', () => {
        if (!navigator.geolocation) {
            Swal.fire({ icon: 'error', title: 'Não suportado', text: 'Seu navegador não permite obter a localização.' });
            return;
        }

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                inputLat.value = pos.coords.latitude.toFixed(7);
                inputLng.value = pos.coords.longitude.toFixed(7);
            },
            () => {
                Swal.fire({ icon: 'error', title: 'Não foi possível obter', text: 'Verifique a permissão de localização do navegador.' });
            }
        );
    });
});