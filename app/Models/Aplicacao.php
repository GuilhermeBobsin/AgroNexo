<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aplicacao extends Model
{
    protected $table = 'aplicacoes';

    protected $fillable = [
        'talhao_id',
        'produto_id',
        'data_aplicacao',
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
}
