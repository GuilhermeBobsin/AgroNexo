@php
    $tipos = ['trator' => 'Trator', 'implemento' => 'Implemento', 'pulverizador' => 'Pulverizador', 'colheitadeira' => 'Colheitadeira', 'outro' => 'Outro'];
    $statusLabels = ['disponivel' => 'Disponível', 'em_uso' => 'Em uso', 'manutencao' => 'Em manutenção'];
@endphp

<div class="mb-3">
    <label for="propriedade_id" class="form-label">Propriedade</label>
    <select id="propriedade_id" name="propriedade_id" class="form-select @error('propriedade_id') is-invalid @enderror" required>
        <option value="">Selecione uma propriedade</option>
        @foreach ($propriedades as $propriedade)
            <option value="{{ $propriedade->id }}" @selected(old('propriedade_id', $recurso?->propriedade_id) == $propriedade->id)>{{ $propriedade->nome }}</option>
        @endforeach
    </select>
    @error('propriedade_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
    <label for="nome" class="form-label">Nome do recurso</label>
    <input id="nome" name="nome" type="text" value="{{ old('nome', $recurso?->nome) }}" class="form-control @error('nome') is-invalid @enderror" placeholder="Ex: Trator John Deere 5078E" maxlength="255" required>
    @error('nome')<div class="invalid-feedback">{{ $message }}</div>@enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="tipo" class="form-label">Tipo</label>
        <select id="tipo" name="tipo" class="form-select @error('tipo') is-invalid @enderror" required>
            @foreach ($tipos as $valor => $rotulo)
                <option value="{{ $valor }}" @selected(old('tipo', $recurso?->tipo ?? 'outro') === $valor)>{{ $rotulo }}</option>
            @endforeach
        </select>
        @error('tipo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6 mb-3">
        <label for="status" class="form-label">Status</label>
        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror" required>
            @foreach ($statusLabels as $valor => $rotulo)
                <option value="{{ $valor }}" @selected(old('status', $recurso?->status ?? 'disponivel') === $valor)>{{ $rotulo }}</option>
            @endforeach
        </select>
        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
</div>
