@extends('layouts.admin.base')

@section('content')
<main class="page-body">
    <div class="container-xl">
        <div class="page-header d-print-none mb-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle"><a href="{{ route('admin.propriedades.show', $propriedade) }}">{{ $propriedade->nome }}</a></div>
                    <h2 class="page-title">Talhões</h2>
                </div>
                <div class="col-auto"><a class="btn btn-primary" href="{{ route('admin.propriedades.talhoes.create', $propriedade) }}">Novo talhão</a></div>
            </div>
        </div>

        @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead><tr><th>Talhão</th><th>Cultura</th><th>Área</th><th class="w-1">Ações</th></tr></thead>
                    <tbody>
                    @forelse ($talhoes as $talhao)
                        <tr>
                            <td><a href="{{ route('admin.propriedades.talhoes.show', [$propriedade, $talhao]) }}">{{ $talhao->nome }}</a></td>
                            <td>{{ $talhao->cultura?->nome ?? 'Não definida' }}</td>
                            <td>{{ number_format((float) $talhao->area, 2, ',', '.') }} ha</td>
                            <td class="text-nowrap">
                                <a class="btn btn-sm" href="{{ route('admin.propriedades.talhoes.edit', [$propriedade, $talhao]) }}">Editar</a>
                                <form class="d-inline" method="POST" action="{{ route('admin.propriedades.talhoes.destroy', [$propriedade, $talhao]) }}" onsubmit="return confirm('Excluir este talhão?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Excluir</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-secondary py-5">Nenhum talhão cadastrado nesta propriedade.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if ($talhoes->hasPages()) <div class="card-footer">{{ $talhoes->links() }}</div> @endif
        </div>
    </div>
</main>
@endsection
