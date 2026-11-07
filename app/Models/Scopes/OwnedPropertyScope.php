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
        // Never call Auth::user() here: when Laravel is loading the session user,
        // that call would query User again and re-enter this global scope forever.
        $guard = Auth::guard();
        $user = method_exists($guard, 'hasUser') && $guard->hasUser()
            ? $guard->getUser()
            : null;

        if (!$user || $user->perfil !== 'admin') {
            return;
        }

        $propertyIds = DB::table('propriedade_usuario')->where('usuario_id', $user->id)
            ->where('papel', 'dono')->select('propriedade_id');
        $table = $model->getTable();

        if ($table === 'propriedades') {
            $builder->whereIn($model->qualifyColumn('id'), $propertyIds);
        } elseif ($table === 'aplicacoes') {
            $builder->whereIn($model->qualifyColumn('talhao_id'), DB::table('talhoes')
                ->whereIn('propriedade_id', $propertyIds)->select('id'));
        } elseif ($table === 'movimentacoes_estoque') {
            $builder->whereIn($model->qualifyColumn('produto_propriedade_id'), DB::table('produto_propriedade')
                ->whereIn('propriedade_id', $propertyIds)->select('id'));
        } elseif ($table === 'users') {
            // Use a plain pivot subquery. Eloquent whereHas('propriedades') recursively
            // applies both the User and Propriedade global scopes and can exhaust memory.
            $userIds = DB::table('propriedade_usuario')
                ->whereIn('propriedade_id', $propertyIds)->select('usuario_id');
            $builder->where(function (Builder $query) use ($model, $user, $userIds) {
                // A newly registered admin may not have created a property yet,
                // but must still be able to load their own account record.
                $query->where($model->qualifyColumn('id'), $user->getAuthIdentifier())
                    ->orWhereIn($model->qualifyColumn('id'), $userIds);
            });
        } elseif ($table === 'produtos') {
            $builder->whereIn($model->qualifyColumn('id'), DB::table('produto_propriedade')
                ->whereIn('propriedade_id', $propertyIds)->select('produto_id'));
        } else {
            $builder->whereIn($model->qualifyColumn('propriedade_id'), $propertyIds);
        }
    }
}
