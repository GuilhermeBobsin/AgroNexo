<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    protected $table = 'alertas';

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
}
