<div id="clima-preview" class="mb-3" data-endpoint="{{ route('admin.inteligencia.avaliar') }}" data-tipo-default="{{ $tipoClimaPadrao ?? '' }}" data-talhao-default="{{ $talhaoClimaPadrao ?? '' }}">
    <div class="form-hint">Selecione o tipo, talhão e data para consultar a indicação climática.</div>
</div>
<script type="module">
    const painelClima = document.getElementById('clima-preview');
    if (painelClima) {
        const controles = ['tipo', 'talhao_id', 'data_prevista', 'hora_prevista'].map((id) => document.getElementById(id));
        let consultaClima;
        let versaoConsulta = 0;
        const estilos = { indicado: 'success', atencao: 'warning', nao_indicado: 'danger', indisponivel: 'secondary' };
        const rotulos = { indicado: 'Favorável', atencao: 'Atenção', nao_indicado: 'Desfavorável', indisponivel: 'Sem dados suficientes' };
        const atualizarClima = async () => {
            const [tipoCampo, talhaoCampo, data, hora] = controles.map((el) => el?.value ?? '');
            const tipo = tipoCampo || painelClima.dataset.tipoDefault;
            const talhao = talhaoCampo || painelClima.dataset.talhaoDefault;
            const versao = ++versaoConsulta;
            clearTimeout(consultaClima);
            painelClima.replaceChildren();
            if (!tipo || !talhao || !data || !['aplicacao', 'aracao', 'calagem'].includes(tipo)) {
                const texto = document.createElement('div');
                texto.className = 'form-hint';
                texto.textContent = 'A indicação será exibida quando tipo, talhão e data estiverem preenchidos.';
                painelClima.append(texto);
                return;
            }
            consultaClima = setTimeout(async () => {
                const mensagem = document.createElement('div');
                mensagem.className = 'alert alert-secondary';
                mensagem.textContent = 'Consultando as condições previstas…';
                painelClima.replaceChildren(mensagem);
                try {
                    const url = new URL(painelClima.dataset.endpoint, window.location.origin);
                    url.search = new URLSearchParams({ tipo, talhao_id: talhao, data_prevista: data, hora_prevista: hora });
                    const resposta = await fetch(url, { headers: { Accept: 'application/json' } });
                    const dados = await resposta.json();
                    if (!resposta.ok) throw new Error('Não foi possível avaliar a previsão para essa tarefa.');
                    if (versao !== versaoConsulta) return;
                    const caixa = document.createElement('div');
                    caixa.className = 'alert alert-' + (estilos[dados.status] || 'secondary');
                    const titulo = document.createElement('strong');
                    titulo.textContent = rotulos[dados.status] || 'Orientação climática';
                    const texto = document.createElement('div');
                    texto.textContent = dados.mensagem;
                    caixa.append(titulo, texto);
                    (dados.alertas || []).forEach((alerta) => {
                        const item = document.createElement('div');
                        item.className = 'small mt-1';
                        item.textContent = '• ' + alerta;
                        caixa.append(item);
                    });
                    const nota = document.createElement('div');
                    nota.className = 'small mt-2';
                    nota.textContent = 'Orientação de apoio: não bloqueia a tarefa. Confirme as condições no local e siga a bula/receita.';
                    caixa.append(nota);
                    painelClima.replaceChildren(caixa);
                } catch (erro) {
                    if (versao !== versaoConsulta) return;
                    const caixa = document.createElement('div');
                    caixa.className = 'alert alert-secondary';
                    caixa.textContent = erro.message || 'Previsão indisponível; confirme as condições no campo.';
                    painelClima.replaceChildren(caixa);
                }
            }, 250);
        };
        controles.forEach((controle) => controle?.addEventListener('change', atualizarClima));
        controles.slice(0, 3).forEach((controle) => controle?.addEventListener('input', atualizarClima));
        atualizarClima();
    }
</script>
