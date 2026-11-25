<?php

namespace App\Models;

use App\Models\Scopes\OwnedPropertyScope;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new OwnedPropertyScope);
    }

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
