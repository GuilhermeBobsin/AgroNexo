<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegraClimatica extends Model
{
    use HasFactory;

    protected $table = 'regras_climaticas';

    protected $fillable = [
        'nome',
        'tipo_tarefa',
        'variavel',
        'agregacao',
        'janela_horas',
        'operador',
        'valor',
        'unidade',
        'gravidade',
        'mensagem',
        'ativa',
    ];

    protected $casts = [
        'ativa' => 'boolean',
        'valor' => 'decimal:2',
        'janela_horas' => 'integer',
    ];
}
