<div class="mb-3">
    <label for="propriedade_id" class="form-label">Propriedade</label>
    <select id="propriedade_id" name="propriedade_id" class="form-select @error('propriedade_id') is-invalid @enderror" required>
        <option value="">Selecione uma propriedade</option>
        @foreach ($propriedades as $propriedade)
            <option value="{{ $propriedade->id }}" @selected(old('propriedade_id', $estoque->propriedade_id ?? '') == $propriedade->id)>{{ $propriedade->nome }}</option>
        @endforeach
    </select>
    <div class="invalid-feedback" id="error-propriedade_id">@error('propriedade_id'){{ $message }}@enderror</div>
</div>

<div class="mb-3">
    <label for="produto_id" class="form-label">Produto</label>
    <select id="produto_id" name="produto_id" class="form-select @error('produto_id') is-invalid @enderror" required>
        <option value="">Selecione um produto</option>
        @foreach ($produtos as $produto)
            <option value="{{ $produto->id }}" @selected(old('produto_id', $estoque->produto_id ?? '') == $produto->id)>{{ $produto->nome }} ({{ $produto->unidade }})</option>
        @endforeach
    </select>
    <div class="invalid-feedback" id="error-produto_id">@error('produto_id'){{ $message }}@enderror</div>
    <div class="form-hint">Cada produto pode ter um registro de estoque por propriedade.</div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="estoque_atual" class="form-label">Quantidade atual</label>
        <input id="estoque_atual" name="estoque_atual" type="number" min="0" max="999999999.999" step="0.001" value="{{ old('estoque_atual', $estoque->estoque_atual ?? '0.000') }}" class="form-control @error('estoque_atual') is-invalid @enderror" required>
        <div class="invalid-feedback" id="error-estoque_atual">@error('estoque_atual'){{ $message }}@enderror</div>
    </div>
    <div class="col-md-6 mb-3">
        <label for="estoque_minimo" class="form-label">Estoque mínimo</label>
        <input id="estoque_minimo" name="estoque_minimo" type="number" min="0" max="999999999.999" step="0.001" value="{{ old('estoque_minimo', $estoque->estoque_minimo ?? '0.000') }}" class="form-control @error('estoque_minimo') is-invalid @enderror" required>
        <div class="invalid-feedback" id="error-estoque_minimo">@error('estoque_minimo'){{ $message }}@enderror</div>
    </div>
</div>

<div class="mb-3">
    <label for="data_validade" class="form-label">Data de validade <span class="text-secondary">(opcional)</span></label>
    <input id="data_validade" name="data_validade" type="date" value="{{ old('data_validade', isset($estoque->data_validade) ? \Illuminate\Support\Carbon::parse($estoque->data_validade)->format('Y-m-d') : '') }}" class="form-control @error('data_validade') is-invalid @enderror">
    <div class="invalid-feedback" id="error-data_validade">@error('data_validade'){{ $message }}@enderror</div>
</div>
