@extends('layouts.admin.base')

@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div class="row align-items-center"><div class="col"><div class="text-secondary">Execução das atividades</div><h1 class="page-title">Minhas tarefas</h1><div class="text-secondary mt-1">Atualize o andamento e registre os dados da aplicação ao concluir.</div></div><div class="col-auto"><a href="{{ route('operador.tarefas.index') }}" class="btn btn-primary">Abrir lista de tarefas</a></div></div></div>
    <div class="row row-cards mb-4">
        @foreach ([['Aguardando início', $indicadores['pendentes'], 'yellow'], ['Em andamento', $indicadores['em_andamento'], 'blue'], ['Concluídas neste mês', $indicadores['concluidas_mes'], 'green']] as [$label, $value, $color])
            <div class="col-sm-6 col-xl-4"><div class="card"><div class="card-body"><div class="subheader">{{ $label }}</div><div class="h1 mb-0 text-{{ $color }}">{{ number_format($value, 0, ',', '.') }}</div></div></div></div>
        @endforeach
    </div>
    <div class="card"><div class="card-header"><h2 class="card-title">Próximas atividades</h2><div class="card-actions"><a href="{{ route('operador.tarefas.index') }}">Ver todas</a></div></div>
        <div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Tarefa</th><th>Propriedade / talhão</th><th>Data prevista</th><th>Status</th><th></th></tr></thead><tbody>
            @forelse ($proximasTarefas as $tarefa)<tr><td class="fw-semibold">{{ $tarefa->titulo }}</td><td>{{ $tarefa->propriedade->nome }}<div class="text-secondary small">{{ $tarefa->talhao?->nome ?? '—' }}</div></td><td>{{ $tarefa->data_prevista?->format('d/m/Y') }} {{ $tarefa->hora_prevista ? substr($tarefa->hora_prevista, 0, 5) : '' }}</td><td>{{ $tarefa->status === 'em_andamento' ? 'Em andamento' : 'Pendente' }}</td><td><a class="btn btn-sm" href="{{ route('operador.tarefas.show', $tarefa) }}">Abrir</a></td></tr>
            @empty<tr><td colspan="5" class="text-center text-secondary py-5">Você não tem tarefas abertas no momento.</td></tr>@endforelse
        </tbody></table></div>
    </div>
</div></main>
@endsection
