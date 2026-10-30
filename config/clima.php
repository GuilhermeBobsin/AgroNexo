<?php

return [
    'open_meteo' => [
        'base_url' => env('OPEN_METEO_BASE_URL', 'https://api.open-meteo.com/v1'),
        'api_key' => env('OPEN_METEO_API_KEY'),
    ],
    // Limites provisórios no servidor, sujeitos à validação agronômica e calibração por tipo de solo.
    'dias_previsao' => 7,
    'dias_historico' => 7,
    'idade_maxima_horas' => 2,
    'pulverizacao' => [
        'vento_minimo_favoravel_kmh' => 3,
        'vento_maximo_favoravel_kmh' => 6.5,
        'vento_maximo_atencao_kmh' => 9.5,
        'umidade_relativa_minima' => 55,
        'temperatura_maxima' => 30,
        'chuva_probabilidade_alerta' => 50,
        'chuva_apos_aplicacao_horas' => 2,
        'hora_inicio' => 6,
        'hora_fim' => 18,
    ],
    'aracao' => [
        // Faixa provisória em m³/m³, usar como alerta da estimativa superficial (3–9 cm).
        // A friabilidade real varia com textura, estrutura e matéria orgânica; confirmar com teste de campo.
        'umidade_solo_friavel_minima' => 0.12,
        'umidade_solo_friavel_maxima' => 0.30,
        'chuva_probabilidade_alerta' => 50,
    ],
    'calagem' => [
        'vento_maximo_favoravel_kmh' => 10,
        'vento_maximo_atencao_kmh' => 15,
        'vento_maximo_desfavoravel_kmh' => 30,
        'chuva_probabilidade_alerta' => 50,
    ],
];
