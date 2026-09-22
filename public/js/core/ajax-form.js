import { showSuccess, showError } from './toast.js';

function initAjaxForm(form) {
    const btn = form.querySelector('[type="submit"]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach((el) => (el.textContent = ''));

        const textoOriginal = btn.textContent;
        btn.disabled = true;
        btn.textContent = 'Salvando...';

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: new FormData(form),
            });

            const data = await response.json();

            if (response.status === 422) {
                Object.entries(data.errors).forEach(([campo, mensagens]) => {
                    form.querySelector(`#input-${campo}`)?.classList.add('is-invalid');
                    const feedback = form.querySelector(`#error-${campo}`);
                    if (feedback) feedback.textContent = mensagens[0];
                });
                showError('Verifique os campos', 'Alguns dados precisam de ajuste antes de continuar.');
                return;
            }

            if (!response.ok) {
                showError('Não foi possível concluir', data.message || 'Tente novamente em instantes.');
                return;
            }

            showSuccess(data.message);
            form.reset();
            form.dispatchEvent(new CustomEvent('ajax-success', { detail: data }));
        } catch (err) {
            console.error(err);
            showError('Erro de conexão', 'Não foi possível se conectar ao servidor.');
        } finally {
            btn.disabled = false;
            btn.textContent = textoOriginal;
        }
    });
}

export function initAjaxForms(selector = 'form[data-ajax-form]') {
    document.querySelectorAll(selector).forEach(initAjaxForm);
}