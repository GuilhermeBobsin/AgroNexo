<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Propriedade extends Model
{

    protected $table = 'propriedades';

    protected $fillable = [
        'nome',
        'responsavel',
        'localizacao',
        'latitude',
        'longitude',
    ];
}
