<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeituraClimatica extends Model
{
    use HasFactory;

    protected $fillable = [
        'talhao_id',
        'temperatura',
        'umidade',
        'velocidade_vento',
        'precipitacao',
        'chance_chuva',
        'coletado_em',
    ];

    protected $casts = [
        'coletado_em' => 'datetime',
    ];

    public function talhao(): BelongsTo
    {
        return $this->belongsTo(Talhao::class);
    }
}
