@extends('layouts.admin.base')

@section('content')
@php $statusLabels = ['realizada' => 'Realizada', 'planejada' => 'Planejada', 'cancelada' => 'Cancelada']; $statusColors = ['realizada' => 'green', 'planejada' => 'yellow', 'cancelada' => 'red']; @endphp
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><div class="page-pretitle">Operação</div><h1 class="page-title">Histórico de aplicações</h1><div class="text-secondary mt-1">Consulte os registros de aplicação nas propriedades vinculadas a você.</div></div></div>
    <form method="GET" class="card card-body mb-3" aria-label="Filtrar aplicações">
        <div class="row g-2 align-items-end">
            <div class="col-sm-6 col-lg-3"><label class="form-label" for="propriedade_id">Propriedade</label><select class="form-select" id="propriedade_id" name="propriedade_id"><option value="">Todas</option>@foreach ($propriedades as $propriedade)<option value="{{ $propriedade->id }}" @selected(request('propriedade_id') == $propriedade->id)>{{ $propriedade->nome }}</option>@endforeach</select></div>
            <div class="col-sm-6 col-lg-2"><label class="form-label" for="status">Status</label><select class="form-select" id="status" name="status"><option value="">Todos</option>@foreach ($statusLabels as $value => $label)<option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>@endforeach</select></div>
            <div class="col-sm-6 col-lg-2"><label class="form-label" for="data_inicio">De</label><input class="form-control" type="date" id="data_inicio" name="data_inicio" value="{{ request('data_inicio') }}"></div>
            <div class="col-sm-6 col-lg-2"><label class="form-label" for="data_fim">Até</label><input class="form-control" type="date" id="data_fim" name="data_fim" value="{{ request('data_fim') }}"></div>
            <div class="col-auto"><button class="btn btn-primary">Filtrar</button>@if (request()->query()) <a class="btn" href="{{ route('agronomo.aplicacoes.index') }}">Limpar</a>@endif</div>
        </div>
    </form>
    <div class="card"><div class="table-responsive"><table class="table table-vcenter card-table">
        <thead><tr><th>Data</th><th>Propriedade / talhão</th><th>Cultura</th><th>Produto / quantidade</th><th>Operador</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse ($aplicacoes as $aplicacao)
                <tr>
                    <td>{{ $aplicacao->data_aplicacao->format('d/m/Y') }}{{ $aplicacao->hora_aplicacao ? ' · '.substr($aplicacao->hora_aplicacao, 0, 5) : '' }}</td>
                    <td>{{ $aplicacao->talhao->propriedade->nome }}<div class="text-secondary small">{{ $aplicacao->talhao->nome }}</div></td>
                    <td>{{ $aplicacao->talhao->cultura?->nome ?? '—' }}</td>
                    <td>{{ $aplicacao->produto->nome }}<div class="text-secondary small">{{ number_format((float) $aplicacao->dose, 3, ',', '.') }} {{ $aplicacao->produto->unidade }}</div></td>
                    <td>{{ $aplicacao->usuario->name }}</td>
                    <td><span class="badge bg-{{ $statusColors[$aplicacao->status] ?? 'secondary' }}-lt">{{ $statusLabels[$aplicacao->status] ?? $aplicacao->status }}</span></td>
                    <td><a class="btn btn-sm" href="{{ route('agronomo.aplicacoes.show', $aplicacao) }}">Detalhes</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-secondary py-5">Nenhuma aplicação encontrada para as propriedades e filtros selecionados.</td></tr>
            @endforelse
        </tbody>
    </table></div></div>
    <div class="mt-3">{{ $aplicacoes->links() }}</div>
</div></main>
@endsection
