document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-editar-usuario');

    form?.addEventListener('ajax-success', (event) => {
        event.detail.toastPromise.then(() => {
            window.location.assign(event.detail.redirect || form.dataset.redirect);
        });
    });
});
