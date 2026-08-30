<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'principio_ativo',
        'grupo_modo_acao',
        'unidade',
        'preco',
        'estoque_atual',
        'estoque_minimo',
        'data_validaede',
    ];
}
