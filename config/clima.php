<?php

return [
    // Limites provisórios no servidor, sujeitos à validação agronômica e calibração por tipo de solo.
    'dias_previsao' => 7,
    'dias_historico' => 7,
    'pulverizacao' => [
        'vento_minimo_kmh' => 3,
        'vento_maximo_kmh' => 10,
        'hora_inicio' => 6,
        'hora_fim' => 18,
    ],
    'aracao' => [
        'dias_sol_apos_chuva' => 3,
        'sol_minimo_segundos_dia' => 14400,
        'precipitacao_dia_seco_mm' => 1,
        'dias_secos_alerta' => 7,
        'dias_chuvosos_alerta' => 3,
        'umidade_solo_muito_seco' => 0.12,
        'chuva_forte_dia_mm' => 20,
    ],
    'calagem' => [
        'vento_maximo_kmh' => 8,
        'umidade_solo_minima' => 0.12,
        'umidade_solo_maxima' => 0.30,
        'chuva_forte_dia_mm' => 20,
        'chuva_forte_hora_mm' => 10,
    ],
];
