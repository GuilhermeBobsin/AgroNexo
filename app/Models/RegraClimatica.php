<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegraClimatica extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'variavel',
        'operador',
        'valor',
        'gravidade',
        'mensagem',
        'ativa',
    ];

    protected $casts = [
        'ativa' => 'boolean',
        'valor' => 'decimal:2',
    ];
}
