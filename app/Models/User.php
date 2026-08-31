<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'perfil',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function propriedades(): BelongsToMany
    {
        return $this->belongsToMany(Propriedade::class, 'propriedade_usuario', 'usuario_id', 'propriedade_id')
            ->withPivot('papel')
            ->withTimestamps();
    }

    public function aplicacoes(): HasMany
    {
        return $this->hasMany(Aplicacao::class, 'usuario_id');
    }
}
