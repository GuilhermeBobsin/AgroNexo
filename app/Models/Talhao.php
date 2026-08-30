<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Talhao extends Model
{
    protected $table = 'talhoes';

    protected $fillable = [
        'propriedade_id',
        'cultura_id',
        'nome',
        'area',
        'latitude',
        'longitude',
    ];
}
