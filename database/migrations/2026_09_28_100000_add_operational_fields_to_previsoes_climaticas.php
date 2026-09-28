<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('previsoes_climaticas', function (Blueprint $table) {
            $table->enum('tipo_dado', ['historico_estimado', 'previsao'])->default('previsao')->after('previsto_para');
            $table->decimal('duracao_sol', 8, 2)->nullable()->after('chance_chuva');
            $table->decimal('umidade_solo', 6, 4)->nullable()->after('duracao_sol');
            $table->unsignedSmallInteger('codigo_tempo')->nullable()->after('umidade_solo');
            $table->index(['talhao_id', 'tipo_dado', 'previsto_para'], 'previsoes_talhao_tipo_data_idx');
        });
    }

    public function down(): void
    {
        Schema::table('previsoes_climaticas', function (Blueprint $table) {
            $table->dropIndex('previsoes_talhao_tipo_data_idx');
            $table->dropColumn(['tipo_dado', 'duracao_sol', 'umidade_solo', 'codigo_tempo']);
        });
    }
};
