document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.btn-editar-cultura').forEach((btn) => {
        btn.addEventListener('click', () => {
            document.getElementById('form-editar-cultura').action = btn.dataset.url;
            document.getElementById('edit-input-nome').value = btn.dataset.nome;
        });
    });

    document.getElementById('form-nova-cultura')?.addEventListener('ajax-success', (e) => {
        e.detail.toastPromise.then(() => window.location.reload());
    });

    document.getElementById('form-editar-cultura')?.addEventListener('ajax-success', (e) => {
        e.detail.toastPromise.then(() => window.location.reload());
    });

    document.querySelectorAll('.btn-excluir-cultura').forEach((btn) => {
        btn.addEventListener('click', async () => {
            const { nome, url, talhoes } = btn.dataset;
            const qtdTalhoes = parseInt(talhoes || '0', 10);

            const texto = qtdTalhoes > 0
                ? `${qtdTalhoes} ${qtdTalhoes === 1 ? 'talhão perderá' : 'talhões perderão'} essa cultura, mas não serão excluídos.`
                : 'Essa ação não pode ser desfeita.';

            const result = await Swal.fire({
                icon: 'warning',
                title: `Excluir "${nome}"?`,
                text: texto,
                showCancelButton: true,
                confirmButtonText: 'Excluir',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d63939',
            });

            if (!result.isConfirmed) return;

            try {
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                        Accept: 'application/json',
                    },
                });

                const data = await response.json();

                if (!response.ok) {
                    Swal.fire({ icon: 'error', title: 'Não foi possível excluir', text: data.message });
                    return;
                }

                await Swal.fire({ icon: 'success', title: 'Excluída!', timer: 1500, showConfirmButton: false });
                window.location.reload();
            } catch (err) {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Erro de conexão', text: 'Não foi possível se conectar ao servidor.' });
            }
        });
    });
});