<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('previsoes_climaticas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('talhao_id')->constrained('talhoes')->cascadeOnDelete();
            $table->dateTime('previsto_para');
            $table->decimal('temperatura', 5, 2)->nullable();
            $table->decimal('umidade', 5, 2)->nullable();
            $table->decimal('velocidade_vento', 6, 2)->nullable();
            $table->decimal('rajada_vento', 6, 2)->nullable();
            $table->decimal('precipitacao', 8, 2)->nullable();
            $table->decimal('chance_chuva', 5, 2)->nullable();
            $table->string('fonte')->default('open-meteo');
            $table->dateTime('atualizado_em');
            $table->timestamps();
            $table->unique(['talhao_id', 'previsto_para']);
            $table->index(['previsto_para', 'talhao_id']);
        });

        Schema::table('regras_climaticas', function (Blueprint $table) {
            $table->string('tipo_tarefa')->nullable()->after('nome');
            $table->enum('agregacao', ['maximo', 'soma', 'media'])->default('maximo')->after('variavel');
            $table->unsignedSmallInteger('janela_horas')->default(24)->after('agregacao');
            $table->string('unidade', 20)->nullable()->after('valor');
            $table->index(['ativa', 'tipo_tarefa']);
        });
    }

    public function down(): void
    {
        Schema::table('regras_climaticas', function (Blueprint $table) {
            $table->dropIndex(['ativa', 'tipo_tarefa']);
            $table->dropColumn(['tipo_tarefa', 'agregacao', 'janela_horas', 'unidade']);
        });
        Schema::dropIfExists('previsoes_climaticas');
    }
};
