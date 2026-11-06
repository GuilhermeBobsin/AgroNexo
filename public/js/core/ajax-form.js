import { showSuccess, showError } from './toast.js';

function initAjaxForm(form) {
    const btn = form.querySelector('[type="submit"]');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    let submitting = false;
    let succeeded = false;
    let toastPromise = null;

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        if (submitting) return;
        submitting = true;
        succeeded = false;
        toastPromise = null;

        form.querySelectorAll('.is-invalid').forEach((el) => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach((el) => (el.textContent = ''));

        const textoOriginal = btn?.textContent;
        if (btn) {
            btn.disabled = true;
            btn.textContent = 'Salvando...';
        }

        try {
            const response = await fetch(form.action, {
                // Forms use Laravel's _method field for PUT/PATCH/DELETE spoofing.
                method: (form.method || 'POST').toUpperCase(),
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    Accept: 'application/json',
                },
                body: new FormData(form),
            });

            const data = await response.json();

            if (response.status === 422) {
                Object.entries(data.errors || {}).forEach(([campo, mensagens]) => {
                    const campoArray = campo.replace(/\.\d+$/, '[]');
                    const field = form.querySelector(`[name="${CSS.escape(campo)}"]`) || form.querySelector(`[name="${CSS.escape(campoArray)}"]`);
                    field?.classList.add('is-invalid');
                    const feedback = form.querySelector(`#error-${CSS.escape(campo)}, [data-error="${CSS.escape(campo)}"], [data-error="${CSS.escape(campoArray)}"]`);
                    if (feedback) feedback.textContent = mensagens[0];
                });
                showError(data.errors ? 'Verifique os campos' : (form.dataset.errorTitle || 'Não foi possível concluir'), data.message || 'Alguns dados precisam de ajuste antes de continuar.');
                return;
            }

            if (!response.ok) {
                showError('Não foi possível concluir', data.message || 'Tente novamente em instantes.');
                return;
            }

            toastPromise = showSuccess(data.message || 'Alterações salvas com sucesso.');
            form.dispatchEvent(new CustomEvent('ajax-success', { detail: { ...data, toastPromise } }));
            succeeded = true;
        } catch (err) {
            console.error(err);
            showError('Erro de conexão', 'Não foi possível se conectar ao servidor.');
        } finally {
            const liberarBotao = () => {
                submitting = false;
                if (btn) {
                    btn.disabled = false;
                    btn.textContent = textoOriginal;
                }
            };

            if (succeeded && toastPromise) {
                // Keep the form locked while the success notification is visible;
                // its callback may navigate or refresh the page after the toast.
                toastPromise.finally(liberarBotao);
            } else {
                liberarBotao();
            }
        }
    });
}

export function initAjaxForms(selector = 'form[data-ajax-form]') {
    document.querySelectorAll(selector).forEach(initAjaxForm);
}
