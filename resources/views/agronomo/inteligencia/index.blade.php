@extends('layouts.admin.base')
@section('content')
<main class="page-body"><div class="container-xl">
    <div class="page-header mb-4"><div><h1 class="page-title">Inteligência climática</h1><div class="text-secondary">Avisos sobre tarefas nas propriedades vinculadas.</div></div></div>
    <div class="alert alert-info">Fonte: Open-Meteo · última atualização: {{ $atualizadoEm ? \Illuminate\Support\Carbon::parse($atualizadoEm)->format('d/m/Y H:i') : 'ainda não sincronizada' }}. A previsão apoia o planejamento; confirme as condições no campo.</div>
    <div class="card"><div class="card-header"><h2 class="card-title">Tarefas próximas</h2></div><div class="table-responsive"><table class="table table-vcenter card-table"><thead><tr><th>Tarefa</th><th>Propriedade / talhão</th><th>Data</th><th>Avisos climáticos</th></tr></thead><tbody>
        @forelse ($tarefas as $tarefa)<tr><td><a href="{{ route('agronomo.tarefas.show', $tarefa) }}">{{ $tarefa->titulo }}</a><div class="text-secondary small">{{ ucfirst(str_replace('_',' ', $tarefa->tipo)) }}</div></td><td>{{ $tarefa->propriedade->nome }} · {{ $tarefa->talhao->nome ?? 'sem talhão' }}</td><td>{{ $tarefa->data_prevista->format('d/m/Y') }}</td><td>@forelse ($tarefa->alertas_climaticos as $alerta)<div class="text-{{ $alerta['gravidade'] === 'alta' ? 'danger' : 'warning' }}">{{ $alerta['nome'] }}: {{ $alerta['mensagem'] }} ({{ number_format($alerta['valor'],1,',','.') }} {{ $alerta['unidade'] }})</div>@empty@if ($tarefa->clima_disponivel)<span class="text-secondary">Sem alertas pelas regras ativas</span>@else<span class="text-danger">Sem previsão para a janela da tarefa</span>@endif @endforelse</td></tr>@empty<tr><td colspan="4" class="text-secondary">Não há tarefas futuras com status aberto.</td></tr>@endforelse
    </tbody></table></div></div>
</div></main>
@endsection
