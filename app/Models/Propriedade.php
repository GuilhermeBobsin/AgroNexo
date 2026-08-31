<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Propriedade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'localizacao',
        'latitude',
        'longitude',
    ];

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'propriedade_usuario', 'propriedade_id', 'usuario_id')
            ->withPivot('papel')
            ->withTimestamps();
    }

    public function talhoes(): HasMany
    {
        return $this->hasMany(Talhao::class);
    }

    public function alertas(): HasMany
    {
        return $this->hasMany(Alerta::class);
    }

    public function produtos(): BelongsToMany
    {
        return $this->belongsToMany(Produto::class, 'produto_propriedade')
            ->withPivot('estoque_atual', 'estoque_minimo', 'data_validade')
            ->withTimestamps();
    }
}
