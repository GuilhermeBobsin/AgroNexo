@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h2 class="page-title">Nova tarefa</h2><div class="text-secondary mt-1">Atribua uma atividade a um operador.</div></div></div>
    @if ($propriedades->isEmpty() || $usuarios->isEmpty())<div class="alert alert-warning">É necessário ter ao menos uma propriedade e um operador cadastrado para criar uma tarefa.</div>@endif
    <div class="row"><div class="col-lg-9 col-xl-8"><form id="form-tarefa" method="POST" action="{{ route('admin.tarefas.store') }}" data-ajax-form>@csrf
        <div class="card"><div class="card-body">
            <div class="mb-3"><label for="propriedade_id" class="form-label">Propriedade</label><select id="propriedade_id" name="propriedade_id" class="form-select" required><option value="">Selecione</option>@foreach ($propriedades as $propriedade)<option value="{{ $propriedade->id }}">{{ $propriedade->nome }}</option>@endforeach</select><div class="invalid-feedback" id="error-propriedade_id"></div></div>
            <div class="mb-3"><label for="titulo" class="form-label">Título da tarefa</label><input id="titulo" name="titulo" class="form-control" maxlength="255" placeholder="Ex: Aplicação de fungicida no talhão norte" required><div class="invalid-feedback" id="error-titulo"></div></div>
            <div class="row"><div class="col-md-6 mb-3"><label for="tipo" class="form-label">Tipo de atividade</label><select id="tipo" name="tipo" class="form-select" required><option value="">Selecione</option><option value="aplicacao">Aplicação</option><option value="aracao">Aração</option><option value="calagem">Calagem</option><option value="irrigacao">Irrigação</option><option value="manutencao">Manutenção</option><option value="outro">Outro</option></select><div class="invalid-feedback" id="error-tipo"></div></div><div class="col-md-6 mb-3"><label for="talhao_id" class="form-label">Talhão <span class="text-secondary">(opcional)</span></label><select id="talhao_id" name="talhao_id" class="form-select"><option value="">Sem talhão específico</option>@foreach ($propriedades as $propriedade)@foreach ($propriedade->talhoes as $talhao)<option value="{{ $talhao->id }}" data-propriedade="{{ $propriedade->id }}">{{ $talhao->nome }}</option>@endforeach @endforeach</select><div class="invalid-feedback" id="error-talhao_id"></div></div></div>
            <div class="row"><div class="col-md-6 mb-3"><label for="responsavel_id" class="form-label">Operador responsável</label><select id="responsavel_id" name="responsavel_id" class="form-select" required><option value="">Selecione</option>@foreach ($usuarios as $usuario)<option value="{{ $usuario->id }}">{{ $usuario->name }}</option>@endforeach</select><div class="invalid-feedback" id="error-responsavel_id"></div></div><div class="col-md-6 mb-3"><label for="recurso_id" class="form-label">Recurso <span class="text-secondary">(opcional)</span></label><select id="recurso_id" name="recurso_id" class="form-select"><option value="">Sem recurso</option>@foreach ($propriedades as $propriedade)@foreach ($propriedade->recursos as $recurso)<option value="{{ $recurso->id }}" data-propriedade="{{ $propriedade->id }}">{{ $recurso->nome }} ({{ $recurso->status === 'disponivel' ? 'disponível' : str_replace('_', ' ', $recurso->status) }})</option>@endforeach @endforeach</select><div class="invalid-feedback" id="error-recurso_id"></div></div></div>
            <div id="campos-aplicacao" class="row d-none"><div class="col-md-6 mb-3"><label for="produto_id" class="form-label">Produto</label><select id="produto_id" name="produto_id" class="form-select"><option value="">Selecione</option>@foreach ($produtos as $produto)<option value="{{ $produto->id }}">{{ $produto->nome }}</option>@endforeach</select><div class="invalid-feedback" id="error-produto_id"></div></div><div class="col-md-6 mb-3"><label for="dose" class="form-label">Dose</label><input id="dose" name="dose" type="number" min="0.001" step="0.001" class="form-control"><div class="invalid-feedback" id="error-dose"></div></div></div>
            <div class="row"><div class="col-md-6 mb-3"><label for="data_prevista" class="form-label">Data prevista</label><input id="data_prevista" name="data_prevista" type="date" class="form-control" min="{{ now()->toDateString() }}" required><div class="invalid-feedback" id="error-data_prevista"></div></div><div class="col-md-6 mb-3"><label for="hora_prevista" class="form-label">Horário <span class="text-secondary">(opcional)</span></label><input id="hora_prevista" name="hora_prevista" type="time" class="form-control"><div class="invalid-feedback" id="error-hora_prevista"></div></div></div>
            <div class="mb-3"><label for="observacoes" class="form-label">Orientações</label><textarea id="observacoes" name="observacoes" class="form-control" rows="3" maxlength="1000" placeholder="Instruções para o operador"></textarea><div class="invalid-feedback" id="error-observacoes"></div></div>
            <div class="form-footer d-flex gap-2"><a href="{{ route('admin.tarefas.index') }}" class="btn">Cancelar</a><button type="submit" class="btn btn-primary ms-auto" @disabled($propriedades->isEmpty() || $usuarios->isEmpty())>Criar tarefa</button></div>
        </div></div>
    </form></div></div>
</div></main>
<script type="module">
    const propriedade = document.getElementById('propriedade_id');
    const filtros = [document.getElementById('talhao_id'), document.getElementById('recurso_id')];
    const atualizarOpcoes = () => filtros.forEach((select) => {
        [...select.options].forEach((option) => {
            if (!option.dataset.propriedade) return;
            option.hidden = propriedade.value !== option.dataset.propriedade;
            if (option.hidden && option.selected) select.value = '';
        });
    });
    propriedade.addEventListener('change', atualizarOpcoes);
    document.getElementById('tipo').addEventListener('change', (event) => {
        const aplicacao = event.target.value === 'aplicacao';
        document.getElementById('campos-aplicacao').classList.toggle('d-none', !aplicacao);
        document.getElementById('produto_id').required = aplicacao;
        document.getElementById('dose').required = aplicacao;
    });
    document.getElementById('form-tarefa').addEventListener('ajax-success', (event) => event.detail.toastPromise.then(() => window.location.assign(event.detail.redirect)));
</script>
@endsection
