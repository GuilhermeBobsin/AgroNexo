<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\OwnedPropertyScope;

class Recurso extends Model
{
    protected static function booted(): void { static::addGlobalScope(new OwnedPropertyScope()); }
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
