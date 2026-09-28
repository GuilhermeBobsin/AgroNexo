@if (!empty($alertasClimaticos))
    <div class="mb-3">
        @foreach ($alertasClimaticos as $alerta)
            <div class="alert alert-{{ $alerta['gravidade'] === 'alta' ? 'danger' : ($alerta['gravidade'] === 'media' ? 'warning' : 'info') }}">
                <strong>{{ $alerta['nome'] }}</strong> — {{ $alerta['mensagem'] }}
                <div class="small mt-1">Previsão: {{ number_format($alerta['valor'], 1, ',', '.') }} {{ $alerta['unidade'] }} · janela de {{ $alerta['inicio']->format('d/m H:i') }} a {{ $alerta['fim']->format('d/m H:i') }}.</div>
                <div class="small">A previsão é um apoio ao planejamento; confirme as condições no talhão antes da operação.</div>
            </div>
        @endforeach
    </div>
@elseif (isset($climaDisponivel) && !$climaDisponivel && $tarefa->talhao_id && $tarefa->data_prevista->copy()->endOfDay()->isFuture())
    <div class="alert alert-secondary">Ainda não há previsão disponível para a janela desta tarefa. Atualize o clima ou confirme manualmente as condições antes da operação.</div>
@endif
