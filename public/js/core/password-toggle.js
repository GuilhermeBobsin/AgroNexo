export function initPasswordToggles() {
    document.querySelectorAll('.toggle-password').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const input = btn.closest('.input-group').querySelector('input');
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    });
}