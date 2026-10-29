<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimentacaoEstoque extends Model
{
    protected $table = 'movimentacoes_estoque';

    protected $fillable = [
        'produto_propriedade_id', 'tarefa_id', 'usuario_id', 'tipo', 'quantidade',
        'saldo_anterior', 'saldo_atual', 'motivo',
    ];

    public function estoque(): BelongsTo
    {
        return $this->belongsTo(ProdutoPropriedade::class, 'produto_propriedade_id');
    }

    public function tarefa(): BelongsTo
    {
        return $this->belongsTo(Tarefa::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
