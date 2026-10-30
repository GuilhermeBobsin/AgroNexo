@if (!empty($orientacaoClimatica))
    @php
        $classesClima = ['indicado' => 'success', 'atencao' => 'warning', 'nao_indicado' => 'danger', 'indisponivel' => 'secondary'];
        $rotulosClima = ['indicado' => 'Favorável pelas condições previstas', 'atencao' => 'Atenção', 'nao_indicado' => 'Desfavorável pelas condições previstas', 'indisponivel' => 'Sem dados suficientes'];
    @endphp
    <div class="alert alert-{{ $classesClima[$orientacaoClimatica['status']] ?? 'secondary' }}">
        <strong>{{ $rotulosClima[$orientacaoClimatica['status']] ?? 'Orientação climática' }}</strong>
        <div>{{ $orientacaoClimatica['mensagem'] }}</div>
        @foreach (($orientacaoClimatica['alertas'] ?? []) as $alerta)
            <div class="small mt-1">• {{ $alerta }}</div>
        @endforeach
        <div class="small mt-2">Orientação de apoio: não bloqueia a tarefa. Confirme os dados no talhão e siga a bula/receita antes da operação.</div>
    </div>
@endif
