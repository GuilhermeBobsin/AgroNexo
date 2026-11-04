<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Scopes\OwnedPropertyScope;
use Illuminate\Support\Facades\Auth;

class Cultura extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope('tenant', function ($query) {
            if (Auth::user()?->perfil === 'admin') {
                $query->where('culturas.admin_id', Auth::id());
            }
        });
        static::creating(function (Cultura $cultura) {
            if (Auth::user()?->perfil === 'admin') {
                $cultura->admin_id = Auth::id();
            }
        });
    }

    public function talhoes(): HasMany
    {
        return $this->hasMany(Talhao::class);
    }
}
