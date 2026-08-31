<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Alerta extends Model
{
    use HasFactory;

    protected $fillable = [
        'propriedade_id',
        'talhao_id',
        'aplicacao_id',
        'tipo',
        'titulo',
        'mensagem',
        'gravidade',
        'status',
    ];

    public function propriedade(): BelongsTo
    {
        return $this->belongsTo(Propriedade::class);
    }

    public function talhao(): BelongsTo
    {
        return $this->belongsTo(Talhao::class);
    }

    public function aplicacao(): BelongsTo
    {
        return $this->belongsTo(Aplicacao::class);
    }
}
