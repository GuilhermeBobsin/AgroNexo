const escaparHtml = (valor = '') => String(valor).replace(/[&<>"']/g, (caractere) => ({
    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;',
}[caractere]));

const rotulosPerfil = { admin: 'Administrador', agronomo: 'Agrônomo', operador: 'Operador' };
const coresPerfil = { admin: 'green', agronomo: 'purple', operador: 'blue' };

function atualizarContagem(delta) {
    const contador = document.getElementById('contagem-usuarios');
    if (!contador) return;

    const total = Math.max(0, Number(contador.textContent) + delta);
    contador.parentElement.innerHTML = `<span id="contagem-usuarios">${total}</span> ${total === 1 ? 'usuário cadastrado' : 'usuários cadastrados'}`;
}

function atualizarCartao(usuario, botao) {
    const id = usuario.id;
    document.getElementById(`nome-${id}`).textContent = usuario.name;
    document.getElementById(`email-${id}`).textContent = usuario.email;

    const avatar = document.getElementById(`avatar-${id}`);
    avatar.textContent = usuario.name.trim().charAt(0).toLocaleUpperCase('pt-BR');
    avatar.classList.remove('bg-green-lt', 'bg-purple-lt', 'bg-blue-lt', 'bg-secondary-lt');
    avatar.classList.add(`bg-${coresPerfil[usuario.perfil] || 'secondary'}-lt`);

    const perfil = document.getElementById(`perfil-${id}`);
    perfil.textContent = rotulosPerfil[usuario.perfil] || usuario.perfil;
    perfil.classList.remove('bg-green-lt', 'bg-purple-lt', 'bg-blue-lt', 'bg-secondary-lt');
    perfil.classList.add(`bg-${coresPerfil[usuario.perfil] || 'secondary'}-lt`);

    const status = document.getElementById(`status-${id}`);
    status.textContent = usuario.status.charAt(0).toLocaleUpperCase('pt-BR') + usuario.status.slice(1);
    status.classList.remove('bg-green-lt', 'bg-secondary-lt');
    status.classList.add(usuario.status === 'ativo' ? 'bg-green-lt' : 'bg-secondary-lt');

    botao.dataset.nome = usuario.name;
    botao.dataset.email = usuario.email;
    botao.dataset.perfil = usuario.perfil;
    botao.dataset.status = usuario.status;
    botao.dataset.propriedadeIds = usuario.propriedade_ids.join(',');
}

function formularioEdicao(botao, propriedades) {
    const selecionadas = new Set(botao.dataset.propriedadeIds.split(',').filter(Boolean));
    const opcoesPropriedades = propriedades.length
        ? propriedades.map((propriedade) => `
            <label class="form-check text-start mb-2">
                <input class="form-check-input" type="checkbox" name="propriedade_ids[]" value="${propriedade.id}" ${selecionadas.has(String(propriedade.id)) ? 'checked' : ''}>
                <span class="form-check-label">${escaparHtml(propriedade.nome)}</span>
            </label>`).join('')
        : '<div class="text-secondary text-start">Nenhuma propriedade cadastrada.</div>';

    return `
        <form id="form-modal-usuario" class="text-start">
            <div class="mb-3"><label class="form-label" for="modal-user-name">Nome</label><input id="modal-user-name" name="name" class="form-control" value="${escaparHtml(botao.dataset.nome)}" required></div>
            <div class="mb-3"><label class="form-label" for="modal-user-email">E-mail</label><input id="modal-user-email" name="email" type="email" class="form-control" value="${escaparHtml(botao.dataset.email)}" required></div>
            <div class="row">
                <div class="col-sm-6 mb-3"><label class="form-label" for="modal-user-perfil">Perfil</label><select id="modal-user-perfil" name="perfil" class="form-select"><option value="operador" ${botao.dataset.perfil === 'operador' ? 'selected' : ''}>Operador</option><option value="agronomo" ${botao.dataset.perfil === 'agronomo' ? 'selected' : ''}>Agrônomo</option><option value="admin" ${botao.dataset.perfil === 'admin' ? 'selected' : ''}>Administrador</option></select></div>
                <div class="col-sm-6 mb-3"><label class="form-label" for="modal-user-status">Acesso</label><select id="modal-user-status" name="status" class="form-select"><option value="ativo" ${botao.dataset.status === 'ativo' ? 'selected' : ''}>Ativo</option><option value="inativo" ${botao.dataset.status === 'inativo' ? 'selected' : ''}>Inativo</option></select></div>
            </div>
            <div class="mb-3"><label class="form-label">Propriedades permitidas</label><p class="form-hint">Administradores têm acesso global. Para os demais perfis, marque as propriedades acessíveis.</p>${opcoesPropriedades}</div>
            <hr><p class="text-secondary">Para trocar a senha, preencha os dois campos. Deixe em branco para manter a atual.</p>
            <div class="mb-3"><label class="form-label" for="modal-user-password">Nova senha</label><input id="modal-user-password" name="password" type="password" class="form-control" autocomplete="new-password"></div>
            <div><label class="form-label" for="modal-user-password-confirmation">Confirme a nova senha</label><input id="modal-user-password-confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password"></div>
        </form>`;
}

document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    const propriedades = JSON.parse(document.getElementById('propriedades-usuarios')?.textContent || '[]');

    document.querySelectorAll('.btn-editar-usuario').forEach((botao) => {
        botao.addEventListener('click', async () => {
            const resultado = await Swal.fire({
                title: 'Editar usuário',
                html: formularioEdicao(botao, propriedades),
                width: 640,
                showCancelButton: true,
                confirmButtonText: 'Salvar alterações',
                cancelButtonText: 'Cancelar',
                focusConfirm: false,
                showLoaderOnConfirm: true,
                preConfirm: async () => {
                    const form = document.getElementById('form-modal-usuario');
                    if (!form.reportValidity()) return false;

                    const body = new FormData(form);
                    body.append('_method', 'PUT');

                    try {
                        const resposta = await fetch(botao.dataset.url, {
                            method: 'POST',
                            headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json' },
                            body,
                        });
                        const dados = await resposta.json();

                        if (!resposta.ok) {
                            const erros = Object.values(dados.errors || {}).flat();
                            Swal.showValidationMessage(erros.join(' ') || dados.message || 'Não foi possível atualizar o usuário.');
                            return false;
                        }

                        return dados;
                    } catch (erro) {
                        Swal.showValidationMessage('Erro de conexão. Não foi possível atualizar o usuário.');
                        return false;
                    }
                },
            });

            if (!resultado.isConfirmed) return;

            atualizarCartao(resultado.value.user, botao);
            await Swal.fire({ icon: 'success', title: 'Usuário atualizado', text: resultado.value.message, timer: 1800, showConfirmButton: false });
        });
    });

    document.querySelectorAll('.btn-excluir-usuario').forEach((botao) => {
        botao.addEventListener('click', async () => {
            const confirmacao = await Swal.fire({
                icon: 'warning',
                title: `Excluir ${botao.dataset.nome}?`,
                text: 'Usuários com histórico serão preservados e devem ser apenas desativados.',
                showCancelButton: true,
                confirmButtonText: 'Excluir usuário',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d63939',
            });
            if (!confirmacao.isConfirmed) return;

            botao.disabled = true;
            try {
                const resposta = await fetch(botao.dataset.url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, Accept: 'application/json', 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
                    body: new URLSearchParams({ _method: 'DELETE' }),
                });
                const dados = await resposta.json();

                if (!resposta.ok) {
                    await Swal.fire({ icon: 'error', title: 'Não foi possível excluir', text: dados.message || 'Tente novamente.' });
                    return;
                }

                document.getElementById(`usuario-${botao.dataset.id}`)?.remove();
                atualizarContagem(-1);
                await Swal.fire({ icon: 'success', title: 'Usuário excluído', text: dados.message, timer: 1800, showConfirmButton: false });
            } catch (erro) {
                await Swal.fire({ icon: 'error', title: 'Erro de conexão', text: 'Não foi possível excluir o usuário.' });
            } finally {
                botao.disabled = false;
            }
        });
    });
});
