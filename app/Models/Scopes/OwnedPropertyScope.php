<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/** Restricts tenant-owned records to the properties owned by the current admin. */
class OwnedPropertyScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $user = Auth::user();
        if (!$user || $user->perfil !== 'admin') {
            return;
        }

        $propertyIds = DB::table('propriedade_usuario')->where('usuario_id', $user->id)
            ->where('papel', 'dono')->select('propriedade_id');
        $table = $model->getTable();

        if ($table === 'propriedades') {
            $builder->whereIn($model->qualifyColumn('id'), $propertyIds);
        } elseif ($table === 'aplicacoes') {
            $builder->whereHas('talhao', fn (Builder $query) => $query->whereIn('propriedade_id', $propertyIds));
        } elseif ($table === 'movimentacoes_estoque') {
            $builder->whereHas('estoque', fn (Builder $query) => $query->whereIn('propriedade_id', $propertyIds));
        } elseif ($table === 'users') {
            $builder->whereHas('propriedades', fn (Builder $query) => $query
                ->whereIn('propriedades.id', $propertyIds));
        } elseif ($table === 'produtos') {
            $builder->whereHas('propriedades', fn (Builder $query) => $query
                ->whereIn('propriedades.id', $propertyIds));
        } else {
            $builder->whereIn($model->qualifyColumn('propriedade_id'), $propertyIds);
        }
    }
}
