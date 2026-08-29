<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('propriedade_id')
                ->constrained('propriedades')
                ->cascadeOnDelete();

            $table->foreignId('talhao_id')
                ->nullable()
                ->constrained('talhoes')
                ->nullOnDelete();

            $table->foreignId('aplicacao_id')
                ->nullable()
                ->constrained('aplicacoes')
                ->nullOnDelete();

            $table->string('tipo');
            $table->string('titulo');
            $table->text('mensagem');

            $table->enum('gravidade', [
                'baixa',
                'media',
                'alta',
            ])->default('media');

            $table->enum('status', [
                'ativo',
                'visualizado',
                'resolvido',
            ])->default('ativo');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertas');
    }
};
