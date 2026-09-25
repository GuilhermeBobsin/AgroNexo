@extends('layouts.admin.base')

@section('content')
<main class="page-body">
    <div class="container-xl">
        <div class="page-header mb-4">
            <div>
                <div class="page-pretitle">Visão operacional</div>
                <h1 class="page-title">Olá, {{ $usuario->name }}</h1>
                <div class="text-secondary mt-1">Acompanhe as atividades e aplicações das propriedades vinculadas a você.</div>
            </div>
        </div>

        @if ($propriedades->isEmpty())
            <div class="alert alert-info" role="status">
                Nenhuma propriedade está vinculada ao seu usuário. Peça ao administrador para associar as propriedades que você acompanha.
            </div>
        @else
            <div class="row row-cards mb-4">
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm"><div class="card-body"><div class="subheader">Propriedades</div><div class="h1 mb-0">{{ $indicadores['propriedades'] }}</div></div></div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm"><div class="card-body"><div class="subheader">Talhões acompanhados</div><div class="h1 mb-0">{{ $indicadores['talhoes'] }}</div><div class="text-secondary mt-1">{{ number_format((float) $indicadores['area'], 2, ',', '.') }} ha cadastrados</div></div></div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm"><div class="card-body"><div class="subheader">Tarefas em aberto</div><div class="h1 mb-0">{{ $indicadores['tarefas_pendentes'] + $indicadores['tarefas_em_andamento'] }}</div><div class="text-secondary mt-1">{{ $indicadores['tarefas_pendentes'] }} pendentes · {{ $indicadores['tarefas_em_andamento'] }} em andamento</div></div></div>
                </div>
                <div class="col-sm-6 col-lg-3">
                    <div class="card card-sm"><div class="card-body"><div class="subheader">Neste mês</div><div class="h1 mb-0">{{ $indicadores['aplicacoes_mes'] }}</div><div class="text-secondary mt-1">aplicações · {{ $indicadores['tarefas_concluidas_mes'] }} tarefas concluídas</div></div></div>
                </div>
            </div>

            <div class="row row-cards">
                <div class="col-lg-7">
                    <section class="card h-100" aria-labelledby="tarefas-title">
                        <div class="card-header"><h2 class="card-title" id="tarefas-title">Tarefas em aberto</h2><div class="card-actions"><a href="{{ route('agronomo.tarefas.index') }}">Ver todas</a></div></div>
                        <div class="table-responsive">
                            <table class="table table-vcenter card-table">
                                <thead><tr><th>Tarefa</th><th>Propriedade / talhão</th><th>Responsável</th><th>Data</th><th>Status</th></tr></thead>
                                <tbody>
                                    @forelse ($proximasTarefas as $tarefa)
                                        @php
                                            $statusLabel = $tarefa->status === 'em_andamento' ? 'Em andamento' : 'Pendente';
                                            $statusColor = $tarefa->status === 'em_andamento' ? 'blue' : 'yellow';
                                        @endphp
                                        <tr>
                                            <td><div class="fw-medium">{{ $tarefa->titulo }}</div><div class="text-secondary small">{{ ucfirst(str_replace('_', ' ', $tarefa->tipo)) }}</div></td>
                                            <td>{{ $tarefa->propriedade->nome }}<div class="text-secondary small">{{ $tarefa->talhao->nome ?? 'Sem talhão' }}</div></td>
                                            <td>{{ $tarefa->responsavel->name }}</td>
                                            <td>{{ $tarefa->data_prevista->format('d/m/Y') }}{{ $tarefa->hora_prevista ? ' · '.substr($tarefa->hora_prevista, 0, 5) : '' }}</td>
                                            <td><span class="badge bg-{{ $statusColor }}-lt">{{ $statusLabel }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="5" class="text-center text-secondary py-5">Não há tarefas pendentes ou em andamento nas suas propriedades.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>

                <div class="col-lg-5">
                    <section class="card h-100" aria-labelledby="aplicacoes-title">
                        <div class="card-header"><h2 class="card-title" id="aplicacoes-title">Aplicações recentes</h2><div class="card-actions"><a href="{{ route('agronomo.aplicacoes.index') }}">Ver todas</a></div></div>
                        <div class="list-group list-group-flush">
                            @forelse ($aplicacoesRecentes as $aplicacao)
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col text-truncate">
                                            <div class="text-reset d-block fw-medium">{{ $aplicacao->produto->nome }} · {{ $aplicacao->talhao->nome }}</div>
                                            <div class="d-block text-secondary text-truncate mt-1">{{ $aplicacao->talhao->propriedade->nome }} · {{ $aplicacao->usuario->name }}</div>
                                            <span class="badge bg-{{ $aplicacao->status === 'realizada' ? 'green' : ($aplicacao->status === 'cancelada' ? 'red' : 'yellow') }}-lt mt-2">{{ ucfirst($aplicacao->status) }}</span>
                                        </div>
                                        <div class="col-auto text-end">
                                            <div class="fw-medium">{{ number_format((float) $aplicacao->dose, 3, ',', '.') }} {{ $aplicacao->produto->unidade }}</div>
                                            <div class="text-secondary small">{{ $aplicacao->data_aplicacao->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="card-body text-center text-secondary py-5">Ainda não há aplicações registradas nas suas propriedades.</div>
                            @endforelse
                        </div>
                    </section>
                </div>
            </div>

            <section class="mt-4" aria-labelledby="propriedades-title">
                <h2 class="h3 mb-3" id="propriedades-title">Propriedades acompanhadas</h2>
                <div class="row row-cards">
                    @foreach ($propriedades as $propriedade)
                        <div class="col-sm-6 col-xl-4">
                            <article class="card card-sm">
                                <div class="card-body">
                                    <h3 class="card-title mb-1">{{ $propriedade->nome }}</h3>
                                    <div class="text-secondary">{{ $propriedade->localizacao }}</div>
                                    <div class="d-flex gap-3 mt-3 text-secondary small">
                                        <span>{{ $propriedade->talhoes_count }} {{ $propriedade->talhoes_count === 1 ? 'talhão' : 'talhões' }}</span>
                                        <span>{{ $propriedade->tarefas_count }} {{ $propriedade->tarefas_count === 1 ? 'tarefa' : 'tarefas' }} no total</span>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</main>
@endsection
