<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrevisaoClimatica extends Model
{
    protected $table = 'previsoes_climaticas';

    protected $fillable = [
        'talhao_id', 'previsto_para', 'temperatura', 'umidade', 'velocidade_vento',
        'rajada_vento', 'precipitacao', 'chance_chuva', 'fonte', 'atualizado_em',
    ];

    protected $casts = ['previsto_para' => 'datetime', 'atualizado_em' => 'datetime'];

    public function talhao(): BelongsTo
    {
        return $this->belongsTo(Talhao::class);
    }
}
