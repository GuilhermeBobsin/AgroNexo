<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
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
    ];


    public function aplicacoes(): HasMany
    {
        return $this->hasMany(Aplicacao::class);
    }

    public function propriedades(): BelongsToMany
    {
        return $this->belongsToMany(Propriedade::class, 'produto_propriedade')
            ->withPivot('estoque_atual', 'estoque_minimo', 'data_validade')
            ->withTimestamps();
    }
}
