<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdutoPropriedade extends Model
{
    protected $table = 'produto_propriedade';

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(MovimentacaoEstoque::class, 'produto_propriedade_id')->latest();
    }
}
