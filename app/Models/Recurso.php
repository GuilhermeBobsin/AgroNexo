<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    protected $table = 'recursos';

    protected $fillable = [
        'propriedade_id',
        'nome',
        'tipo',
        'status',
    ];

    public function propriedade()
    {
        return $this->belongsTo(Propriedade::class);
    }
}
