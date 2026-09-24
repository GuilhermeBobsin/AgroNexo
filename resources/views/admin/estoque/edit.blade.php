@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h2 class="page-title">Editar estoque</h2><div class="text-secondary mt-1">Atualize o produto, a propriedade, as quantidades ou a validade.</div></div></div>
    <div class="row"><div class="col-lg-8 col-xl-7">
        <form id="form-estoque" method="POST" action="{{ route('admin.estoque.update', $estoque->id) }}" data-ajax-form>@csrf @method('PUT')
            <div class="card"><div class="card-body">
                @include('admin.estoque._form', ['estoque' => $estoque])
                <div class="form-footer d-flex gap-2"><a href="{{ route('admin.estoque.show', $estoque->id) }}" class="btn">Cancelar</a><button type="submit" class="btn btn-primary ms-auto">Salvar alterações</button></div>
            </div></div>
        </form>
    </div></div>
</div></main>
<script type="module">
    document.getElementById('form-estoque')?.addEventListener('ajax-success', (event) => event.detail.toastPromise.then(() => window.location.assign(event.detail.redirect)));
</script>
@endsection
