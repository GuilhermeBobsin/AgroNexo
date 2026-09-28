@php
    $classesClima = ['indicado' => 'success', 'atencao' => 'warning', 'nao_indicado' => 'danger', 'indisponivel' => 'secondary'];
    $rotulosClima = ['indicado' => 'Indicado', 'atencao' => 'Atenção', 'nao_indicado' => 'Não indicado', 'indisponivel' => 'Sem dados'];
@endphp
@forelse ($locais as $local)
    <section class="card mb-4">
        <div class="card-header"><div><h2 class="card-title">{{ $local['propriedade']->nome }} · {{ $local['talhao']->nome }}</h2><div class="text-secondary small">{{ $local['talhao']->area ? number_format((float) $local['talhao']->area, 2, ',', '.') . ' ha' : 'Área não informada' }}</div></div></div>
        <div class="table-responsive"><table class="table table-vcenter card-table">
            <thead><tr><th>Dia</th><th>Pulverização</th><th>Aração</th><th>Calagem</th></tr></thead>
            <tbody>
                @foreach ($local['dias'] as $dia)
                    <tr>
                        <td class="text-nowrap"><strong>{{ ['Sun'=>'Dom','Mon'=>'Seg','Tue'=>'Ter','Wed'=>'Qua','Thu'=>'Qui','Fri'=>'Sex','Sat'=>'Sáb'][$dia['data']->format('D')] }}, {{ $dia['data']->format('d/m') }}</strong></td>
                        @foreach (['pulverizacao', 'aracao', 'calagem'] as $operacao)
                            <td><span class="badge bg-{{ $classesClima[$dia[$operacao]['status']] ?? 'secondary' }}-lt">{{ $rotulosClima[$dia[$operacao]['status']] ?? 'Orientação' }}</span><div class="small text-wrap mt-1">{{ $dia[$operacao]['mensagem'] }}</div></td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table></div>
    </section>
@empty
    <div class="alert alert-warning">Cadastre propriedades, talhões e coordenadas para consultar as condições climáticas.</div>
@endforelse
