<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('culturas', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        $culturas = DB::table('culturas')->orderBy('id')->get();
        foreach ($culturas as $cultura) {
            $owners = DB::table('talhoes')
                ->join('propriedade_usuario', function ($join) {
                    $join->on('propriedade_usuario.propriedade_id', '=', 'talhoes.propriedade_id')
                        ->where('propriedade_usuario.papel', '=', 'dono');
                })
                ->where('talhoes.cultura_id', $cultura->id)
                ->select('propriedade_usuario.usuario_id')
                ->distinct()->pluck('usuario_id')->all();

            if (!$owners) {
                $owners = DB::table('propriedade_usuario')->where('papel', 'dono')->distinct()->pluck('usuario_id')->all();
            }
            if (!$owners) {
                $owners = DB::table('users')->where('perfil', 'admin')->orderBy('id')->limit(1)->pluck('id')->all();
            }
            if (!$owners) continue;

            DB::table('culturas')->where('id', $cultura->id)->update(['admin_id' => $owners[0]]);
            foreach (array_slice($owners, 1) as $ownerId) {
                $cloneId = DB::table('culturas')->insertGetId([
                    'admin_id' => $ownerId,
                    'nome' => $cultura->nome,
                    'created_at' => $cultura->created_at,
                    'updated_at' => $cultura->updated_at,
                ]);
                $propriedadeIds = DB::table('propriedade_usuario')->where('usuario_id', $ownerId)->where('papel', 'dono')->pluck('propriedade_id');
                DB::table('talhoes')->where('cultura_id', $cultura->id)->whereIn('propriedade_id', $propriedadeIds)->update(['cultura_id' => $cloneId]);
            }
        }

        Schema::table('culturas', function (Blueprint $table) {
            $table->unique(['admin_id', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::table('culturas', function (Blueprint $table) {
            $table->dropUnique(['admin_id', 'nome']);
            $table->dropForeign(['admin_id']);
            $table->dropColumn('admin_id');
        });
    }
};
