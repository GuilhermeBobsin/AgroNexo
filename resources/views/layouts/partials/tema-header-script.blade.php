<script>
    (() => {
        const botoes = document.querySelectorAll('.hide-theme-dark, .hide-theme-light');
        const aplicarTema = (tema) => {
            document.documentElement.setAttribute('data-bs-theme', tema);
            localStorage.setItem('theme', tema);
            localStorage.setItem('tabler-theme', tema);
        };

        botoes.forEach((botao) => {
            botao.addEventListener('click', (evento) => {
                evento.preventDefault();
                aplicarTema(botao.classList.contains('hide-theme-dark') ? 'dark' : 'light');
            });
        });
    })();
</script>
