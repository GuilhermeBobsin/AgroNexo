<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegraClimatica extends Model
{
    protected $table = 'regras_climaticas';

    protected $fillable = [
        'nome',
        'variavel',
        'operador',
        'valor',
        'gravidade',
        'mensagem',
        'ativa',
    ];
}
