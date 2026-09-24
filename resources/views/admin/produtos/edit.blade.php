@extends('layouts.admin.base')

@section('content')
<main id="content" class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h2 class="page-title">Editar produto</h2><div class="text-secondary mt-1">Atualize os dados de {{ $produto->nome }}.</div></div></div>
    <div class="row"><div class="col-lg-8 col-xl-7"><form id="form-produto" method="POST" action="{{ route('admin.produtos.update', $produto) }}" data-ajax-form>@csrf @method('PUT')
        <div class="card"><div class="card-body">@include('admin.produtos._form', ['produto' => $produto])<div class="form-footer d-flex gap-2"><a href="{{ route('admin.produtos.show', $produto) }}" class="btn">Cancelar</a><button type="submit" class="btn btn-primary ms-auto">Salvar alterações</button></div></div></div>
    </form></div></div>
</div></main>
<script type="module">document.getElementById('form-produto')?.addEventListener('ajax-success', (event) => event.detail.toastPromise.then(() => window.location.assign(event.detail.redirect)));</script>
@endsection
