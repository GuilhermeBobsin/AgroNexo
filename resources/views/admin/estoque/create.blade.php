@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h2 class="page-title">Novo estoque</h2><div class="text-secondary mt-1">Associe um produto a uma propriedade e informe as quantidades.</div></div></div>
    @if ($produtos->isEmpty() || $propriedades->isEmpty())<div class="alert alert-warning">É necessário cadastrar @if ($produtos->isEmpty())<a href="{{ route('admin.produtos.create') }}">um produto</a>@endif @if ($produtos->isEmpty() && $propriedades->isEmpty())e @endif @if ($propriedades->isEmpty())<a href="{{ route('admin.propriedades.create') }}">uma propriedade</a>@endif antes de lançar o estoque.</div>@endif
    <div class="row"><div class="col-lg-8 col-xl-7">
        <form id="form-estoque" method="POST" action="{{ route('admin.estoque.store') }}" data-ajax-form>@csrf
            <div class="card"><div class="card-body">
                @include('admin.estoque._form', ['estoque' => null])
                <div class="form-footer d-flex gap-2"><a href="{{ route('admin.estoque.index') }}" class="btn">Cancelar</a><button type="submit" class="btn btn-primary ms-auto" @disabled($produtos->isEmpty() || $propriedades->isEmpty())>Cadastrar estoque</button></div>
            </div></div>
        </form>
    </div></div>
</div></main>
<script type="module">
    document.getElementById('form-estoque')?.addEventListener('ajax-success', (event) => event.detail.toastPromise.then(() => window.location.assign(event.detail.redirect)));
</script>
@endsection
