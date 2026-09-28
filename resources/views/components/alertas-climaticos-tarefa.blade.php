@if (!empty($orientacaoClimatica))
    @php
        $classesClima = ['indicado' => 'success', 'atencao' => 'warning', 'nao_indicado' => 'danger', 'indisponivel' => 'secondary'];
        $rotulosClima = ['indicado' => 'Indicado pelas condições previstas', 'atencao' => 'Atenção', 'nao_indicado' => 'Não indicado pelas condições previstas', 'indisponivel' => 'Sem dados suficientes'];
    @endphp
    <div class="alert alert-{{ $classesClima[$orientacaoClimatica['status']] ?? 'secondary' }}">
        <strong>{{ $rotulosClima[$orientacaoClimatica['status']] ?? 'Orientação climática' }}</strong>
        <div>{{ $orientacaoClimatica['mensagem'] }}</div>
        <div class="small mt-1">Estimativa meteorológica para apoiar o planejamento. Confirme vento e condição do solo no talhão antes da operação.</div>
    </div>
@endif
