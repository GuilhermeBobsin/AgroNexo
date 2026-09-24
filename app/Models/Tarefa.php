<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tarefa extends Model
{
    protected $fillable = [
        'propriedade_id',
        'talhao_id',
        'tipo',
        'titulo',
        'responsavel_id',
        'recurso_id',
        'produto_id',
        'dose',
        'status',
        'data_prevista',
        'hora_prevista',
        'data_conclusao',
        'observacoes',
    ];

    protected $casts = [
        'data_prevista' => 'date',
        'data_conclusao' => 'datetime',
        'dose' => 'decimal:3',
    ];

    public function propriedade(): BelongsTo
    {
        return $this->belongsTo(Propriedade::class);
    }

    public function talhao(): BelongsTo
    {
        return $this->belongsTo(Talhao::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function recurso(): BelongsTo
    {
        return $this->belongsTo(Recurso::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function aplicacao(): HasOne
    {
        return $this->hasOne(Aplicacao::class);
    }
}
