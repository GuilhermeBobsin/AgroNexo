<?php

namespace App\Models;

use App\Models\Scopes\OwnedPropertyScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProdutoPropriedade extends Model
{
    protected $table = 'produto_propriedade';

    protected static function booted(): void
    {
        static::addGlobalScope(new OwnedPropertyScope);
    }

    public function movimentacoes(): HasMany
    {
        return $this->hasMany(MovimentacaoEstoque::class, 'produto_propriedade_id')->latest();
    }
}
