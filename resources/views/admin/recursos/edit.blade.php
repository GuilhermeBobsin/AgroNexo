@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body">
    <div class="container-xl">
        <div class="page-header mb-4">
            <div><h2 class="page-title">Editar recurso</h2><div class="text-secondary mt-1">Atualize os dados de {{ $recurso->nome }}.</div></div>
        </div>
        <div class="row"><div class="col-lg-8 col-xl-7">
            <form method="POST" action="{{ route('admin.recursos.update', $recurso) }}" data-ajax-form id="form-recurso">
                @csrf @method('PUT')
                <div class="card"><div class="card-body">
                    @include('admin.recursos._form', ['recurso' => $recurso])
                    <div class="form-footer d-flex gap-2">
                        <a href="{{ route('admin.recursos.show', $recurso) }}" class="btn">Cancelar</a>
                        <button type="submit" class="btn btn-primary ms-auto">Salvar alterações</button>
                    </div>
                </div></div>
            </form>
        </div></div>
    </div>
</main>
<script type="module">
    document.getElementById('form-recurso')?.addEventListener('ajax-success', (event) => {
        event.detail.toastPromise.then(() => window.location.assign(event.detail.redirect));
    });
</script>
@endsection
