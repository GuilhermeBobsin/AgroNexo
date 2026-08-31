<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Produto extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'principio_ativo',
        'grupo_modo_acao',
        'unidade',
        'preco',
        'estoque_atual',
        'estoque_minimo',
        'data_validade',
    ];

    protected $casts = [
        'data_validade' => 'date',
    ];

    public function aplicacoes(): HasMany
    {
        return $this->hasMany(Aplicacao::class);
    }
}
