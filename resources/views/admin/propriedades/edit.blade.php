@extends('layouts.admin.base')
@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h2 class="page-title">Editar propriedade</h2><div class="text-secondary">Atualize os dados de {{ $propriedade->nome }}.</div></div></div>
    @if ($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif
    <div class="row"><div class="col-lg-8"><form method="POST" action="{{ route('admin.propriedades.update', $propriedade) }}">@csrf @method('PUT')
        <div class="card"><div class="card-body">
            <div class="mb-3"><label class="form-label" for="nome">Nome</label><input id="nome" name="nome" class="form-control" value="{{ old('nome', $propriedade->nome) }}" required>@error('nome')<div class="text-danger">{{ $message }}</div>@enderror</div>
            <div class="mb-3"><label class="form-label" for="localizacao">Localização</label><input id="localizacao" name="localizacao" class="form-control" value="{{ old('localizacao', $propriedade->localizacao) }}" required>@error('localizacao')<div class="text-danger">{{ $message }}</div>@enderror</div>
            <div class="row"><div class="col-md-6 mb-3"><label class="form-label" for="latitude">Latitude</label><input id="latitude" name="latitude" type="number" step="any" min="-90" max="90" class="form-control" value="{{ old('latitude', $propriedade->latitude) }}" required>@error('latitude')<div class="text-danger">{{ $message }}</div>@enderror</div>
            <div class="col-md-6 mb-3"><label class="form-label" for="longitude">Longitude</label><input id="longitude" name="longitude" type="number" step="any" min="-180" max="180" class="form-control" value="{{ old('longitude', $propriedade->longitude) }}" required>@error('longitude')<div class="text-danger">{{ $message }}</div>@enderror</div></div>
        </div><div class="card-footer d-flex justify-content-end gap-2"><a href="{{ route('admin.propriedades.show', $propriedade) }}" class="btn">Cancelar</a><button class="btn btn-primary">Salvar alterações</button></div></div>
    </form></div></div>
</div></main>
@endsection
