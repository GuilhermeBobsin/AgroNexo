@extends('layouts.admin.base')

@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><div class="text-secondary"><a href="{{ route('agronomo.recomendacoes.index') }}">Recomendações</a> / Nova</div><h1 class="page-title">Nova recomendação técnica</h1><div class="text-secondary mt-1">O administrador analisará a orientação antes de encaminhá-la como tarefa operacional.</div></div></div>
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    @if ($propriedades->isEmpty())<div class="alert alert-warning">Você ainda não tem propriedade vinculada. Peça ao administrador para atribuir uma propriedade ao seu usuário.</div>@else
    <form method="POST" action="{{ route('agronomo.recomendacoes.store') }}">@csrf
        <div class="card"><div class="card-body">
            <div class="row g-3">
                <div class="col-md-6"><label for="propriedade_id" class="form-label">Propriedade</label><select id="propriedade_id" name="propriedade_id" class="form-select" required><option value="">Selecione</option>@foreach ($propriedades as $propriedade)<option value="{{ $propriedade->id }}" @selected(old('propriedade_id') == $propriedade->id)>{{ $propriedade->nome }}</option>@endforeach</select></div>
                <div class="col-md-6"><label for="talhao_id" class="form-label">Talhão</label><select id="talhao_id" name="talhao_id" class="form-select"><option value="">Toda a propriedade</option>@foreach ($propriedades as $propriedade)@foreach ($propriedade->talhoes as $talhao)<option value="{{ $talhao->id }}" data-propriedade="{{ $propriedade->id }}" @selected(old('talhao_id') == $talhao->id)>{{ $talhao->nome }} — {{ $propriedade->nome }}</option>@endforeach @endforeach</select></div>
                <div class="col-md-8"><label for="titulo" class="form-label">Título</label><input id="titulo" name="titulo" class="form-control" maxlength="255" value="{{ old('titulo') }}" required></div>
                <div class="col-md-2"><label for="tipo" class="form-label">Tipo</label><select id="tipo" name="tipo" class="form-select" required>@foreach (['aplicacao' => 'Aplicação', 'aracao' => 'Aração', 'calagem' => 'Calagem', 'irrigacao' => 'Irrigação', 'manutencao' => 'Manutenção', 'outro' => 'Outro'] as $value => $label)<option value="{{ $value }}" @selected(old('tipo', 'outro') === $value)>{{ $label }}</option>@endforeach</select></div>
                <div class="col-md-2"><label for="prioridade" class="form-label">Prioridade</label><select id="prioridade" name="prioridade" class="form-select" required>@foreach (['baixa' => 'Baixa', 'normal' => 'Normal', 'alta' => 'Alta', 'urgente' => 'Urgente'] as $value => $label)<option value="{{ $value }}" @selected(old('prioridade', 'normal') === $value)>{{ $label }}</option>@endforeach</select></div>
                <div class="col-12"><label for="diagnostico" class="form-label">Diagnóstico / contexto</label><textarea id="diagnostico" name="diagnostico" class="form-control" rows="3" maxlength="5000" required>{{ old('diagnostico') }}</textarea><div class="form-hint">Descreva o problema observado e as condições relevantes.</div></div>
                <div class="col-12"><label for="orientacao" class="form-label">Orientação técnica</label><textarea id="orientacao" name="orientacao" class="form-control" rows="3" maxlength="5000" required>{{ old('orientacao') }}</textarea></div>
                <div class="col-md-8" id="produto-field"><label for="produto_id" class="form-label">Produto sugerido</label><select id="produto_id" name="produto_id" class="form-select"><option value="">Selecione</option>@foreach ($propriedades as $propriedade)@foreach ($propriedade->produtos as $produto)<option value="{{ $produto->id }}" data-propriedade="{{ $propriedade->id }}" @selected(old('produto_id') == $produto->id)>{{ $produto->nome }} · estoque {{ number_format((float) $produto->pivot->estoque_atual, 3, ',', '.') }} {{ $produto->unidade }} — {{ $propriedade->nome }}</option>@endforeach @endforeach</select></div>
                <div class="col-md-4" id="dose-field"><label for="dose" class="form-label">Dose sugerida</label><input id="dose" name="dose" type="number" min="0.001" max="9999999.999" step="0.001" class="form-control" value="{{ old('dose') }}"></div>
            </div>
        </div><div class="card-footer d-flex justify-content-between"><a href="{{ route('agronomo.recomendacoes.index') }}" class="btn">Cancelar</a><button class="btn btn-primary">Enviar para análise</button></div></div>
    </form>@endif
</div></main>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const property = document.getElementById('propriedade_id');
    const talhao = document.getElementById('talhao_id');
    const product = document.getElementById('produto_id');
    const type = document.getElementById('tipo');
    const productField = document.getElementById('produto-field');
    const doseField = document.getElementById('dose-field');
    const updateOptions = (select, propertyId) => {
        [...select.options].forEach((option) => {
            if (!option.dataset.propriedade) return;
            option.hidden = option.dataset.propriedade !== propertyId;
            option.disabled = option.hidden;
            if (option.hidden && option.selected) select.value = '';
        });
    };
    const updateType = () => {
        const application = type.value === 'aplicacao';
        productField.hidden = !application;
        doseField.hidden = !application;
        product.required = application;
        talhao.required = application;
        document.getElementById('dose').required = application;
    };
    property.addEventListener('change', () => { updateOptions(talhao, property.value); updateOptions(product, property.value); });
    type.addEventListener('change', updateType);
    updateOptions(talhao, property.value);
    updateOptions(product, property.value);
    updateType();
});
</script>
@endsection
