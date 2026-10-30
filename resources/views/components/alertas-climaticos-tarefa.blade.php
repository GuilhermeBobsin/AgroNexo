@if (!empty($orientacaoClimatica))
    @php
        $classesClima = ['indicado' => 'success', 'atencao' => 'warning', 'nao_indicado' => 'danger', 'indisponivel' => 'secondary'];
        $rotulosClima = ['indicado' => 'Favorável pelas condições previstas', 'atencao' => 'Atenção', 'nao_indicado' => 'Desfavorável pelas condições previstas', 'indisponivel' => 'Sem dados suficientes'];
        $status = $orientacaoClimatica['status'] ?? 'indisponivel';
    @endphp
    <div class="alert alert-{{ $classesClima[$status] ?? 'secondary' }}" role="alert">
        <div class="d-flex">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon">
                    @switch($status)
                        @case('indicado')
                            <path d="M5 12l5 5l10 -10" />
                            @break
                        @case('atencao')
                            <path d="M12 9v4" />
                            <path d="M10.363 3.591l-8.106 13.534a1.914 1.914 0 0 0 1.636 2.871h16.214a1.914 1.914 0 0 0 1.636 -2.87l-8.106 -13.536a1.914 1.914 0 0 0 -3.274 0z" />
                            <path d="M12 16h.01" />
                            @break
                        @case('nao_indicado')
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M10 10l4 4m0 -4l-4 4" />
                            @break
                        @default
                            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                            <path d="M12 17l0 .01" />
                            <path d="M12 13.5a1.5 1.5 0 0 1 1 -1.5a2.6 2.6 0 1 0 -3 -4" />
                    @endswitch
                </svg>
            </div>
            <div>
                <h4 class="alert-title">{{ $rotulosClima[$status] ?? 'Orientação climática' }}</h4>
                <div class="text-secondary">{{ $orientacaoClimatica['mensagem'] }}</div>

                @if (!empty($orientacaoClimatica['alertas']))
                    <ul class="small mt-2 mb-0 ps-3">
                        @foreach ($orientacaoClimatica['alertas'] as $alerta)
                            <li>{{ $alerta }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="text-secondary small mt-2">
                    Orientação de apoio: não bloqueia a tarefa. Confirme os dados no talhão e siga a bula/receita antes da operação.
                </div>
            </div>
        </div>
    </div>
@endif