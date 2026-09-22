<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Talhao extends Model
{
    use HasFactory;

    protected $table = 'talhoes';
    protected $fillable = [
        'propriedade_id',
        'cultura_id',
        'nome',
        'area',
        'limite'
    ];

    protected $casts = [
        'area' => 'decimal:2',
        'limite' => 'array',
    ];

    public function propriedade(): BelongsTo
    {
        return $this->belongsTo(Propriedade::class);
    }

    public function cultura(): BelongsTo
    {
        return $this->belongsTo(Cultura::class);
    }

    public function aplicacoes(): HasMany
    {
        return $this->hasMany(Aplicacao::class);
    }

    public function alertas(): HasMany
    {
        return $this->hasMany(Alerta::class);
    }

    public function leiturasClimaticas(): HasMany
    {
        return $this->hasMany(LeituraClimatica::class);
    }
}
