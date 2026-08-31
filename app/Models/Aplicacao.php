<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Aplicacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'talhao_id',
        'produto_id',
        'usuario_id',
        'status',
        'dose',
        'equipamento',
        'data_aplicacao',
        'hora_aplicacao',
        'temperatura',
        'umidade',
        'velocidade_vento',
        'precipitacao',
        'observacoes',
    ];

    protected $casts = [
        'data_aplicacao' => 'date',
    ];

    public function talhao(): BelongsTo
    {
        return $this->belongsTo(Talhao::class);
    }

    public function produto(): BelongsTo
    {
        return $this->belongsTo(Produto::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function alertas(): HasMany
    {
        return $this->hasMany(Alerta::class);
    }
}
