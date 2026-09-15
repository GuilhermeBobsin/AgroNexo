document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-criar-usuario');

    form?.addEventListener('ajax-success', () => {
        document.getElementById('input-name')?.focus();
    });
});