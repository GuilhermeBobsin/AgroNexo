<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Recomendacao extends Model
{
    protected $table = 'recomendacoes';

    protected $fillable = [
        'propriedade_id', 'talhao_id', 'agronomo_id', 'analisado_por', 'titulo', 'tipo',
        'prioridade', 'diagnostico', 'orientacao', 'produto_id', 'dose', 'status',
        'parecer_admin', 'analisado_em',
    ];

    protected $casts = [
        'dose' => 'decimal:3',
        'analisado_em' => 'datetime',
    ];

    public function propriedade(): BelongsTo
    {
        return $this->belongsTo(Propriedade::class);
    }

    public function talhao(): BelongsTo
    {
        return $this->belongsTo(Talhao::class);
    }

    public function agronomo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agronomo_id');
    }

    public function analisadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'analisado_por');
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function tarefa(): HasOne
    {
        return $this->hasOne(Tarefa::class);
    }
}
