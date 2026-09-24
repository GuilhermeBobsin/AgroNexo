@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body">
    <div class="container-xl">
        <div class="page-header mb-4">
            <div><h2 class="page-title">Novo recurso</h2><div class="text-secondary mt-1">Cadastre um equipamento e vincule-o a uma propriedade.</div></div>
        </div>

        @if ($propriedades->isEmpty())
            <div class="alert alert-warning">Cadastre uma propriedade antes de adicionar recursos. <a href="{{ route('admin.propriedades.create') }}">Criar propriedade</a></div>
        @endif

        <div class="row"><div class="col-lg-8 col-xl-7">
            <form method="POST" action="{{ route('admin.recursos.store') }}" data-ajax-form id="form-recurso">
                @csrf
                <div class="card"><div class="card-body">
                    @include('admin.recursos._form', ['recurso' => null])
                    <div class="form-footer d-flex gap-2">
                        <a href="{{ route('admin.recursos.index') }}" class="btn">Cancelar</a>
                        <button type="submit" class="btn btn-primary ms-auto" @disabled($propriedades->isEmpty())>Cadastrar recurso</button>
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
