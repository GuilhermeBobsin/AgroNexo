export function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Sucesso!',
        text: message,
        timer: 2000,
        showConfirmButton: false,
    });
}

export function showError(title, message) {
    Swal.fire({ icon: 'error', title, text: message });
}