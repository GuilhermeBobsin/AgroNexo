<div class="mb-3">
    <label for="nome" class="form-label">Nome do produto</label>
    <input id="nome" name="nome" type="text" value="{{ old('nome', $produto?->nome) }}" class="form-control @error('nome') is-invalid @enderror" maxlength="255" placeholder="Ex: Fungicida agrícola" required>
    <div class="invalid-feedback" id="error-nome">@error('nome'){{ $message }}@enderror</div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label for="principio_ativo" class="form-label">Princípio ativo</label><input id="principio_ativo" name="principio_ativo" type="text" value="{{ old('principio_ativo', $produto?->principio_ativo) }}" class="form-control @error('principio_ativo') is-invalid @enderror" maxlength="255"><div class="invalid-feedback" id="error-principio_ativo">@error('principio_ativo'){{ $message }}@enderror</div></div>
    <div class="col-md-6 mb-3"><label for="grupo_modo_acao" class="form-label">Grupo / modo de ação</label><input id="grupo_modo_acao" name="grupo_modo_acao" type="text" value="{{ old('grupo_modo_acao', $produto?->grupo_modo_acao) }}" class="form-control @error('grupo_modo_acao') is-invalid @enderror" maxlength="255"><div class="invalid-feedback" id="error-grupo_modo_acao">@error('grupo_modo_acao'){{ $message }}@enderror</div></div>
</div>
<div class="row">
    <div class="col-md-6 mb-3"><label for="unidade" class="form-label">Unidade de estoque</label><input id="unidade" name="unidade" type="text" value="{{ old('unidade', $produto?->unidade ?? 'L') }}" class="form-control @error('unidade') is-invalid @enderror" maxlength="255" placeholder="L, kg, un..." required><div class="invalid-feedback" id="error-unidade">@error('unidade'){{ $message }}@enderror</div><div class="form-hint">Ex.: L, kg, g ou unidade.</div></div>
    <div class="col-md-6 mb-3"><label for="preco" class="form-label">Preço unitário <span class="text-secondary">(opcional)</span></label><div class="input-group"><span class="input-group-text">R$</span><input id="preco" name="preco" type="number" min="0" max="9999999999.99" step="0.01" value="{{ old('preco', $produto?->preco) }}" class="form-control @error('preco') is-invalid @enderror"></div><div class="invalid-feedback d-block" id="error-preco">@error('preco'){{ $message }}@enderror</div></div>
</div>
