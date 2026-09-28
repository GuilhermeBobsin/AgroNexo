<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recomendacoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propriedade_id')->constrained('propriedades')->cascadeOnDelete();
            $table->foreignId('talhao_id')->nullable()->constrained('talhoes')->nullOnDelete();
            $table->foreignId('agronomo_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('analisado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->string('titulo');
            $table->enum('tipo', ['aplicacao', 'aracao', 'calagem', 'irrigacao', 'manutencao', 'outro']);
            $table->enum('prioridade', ['baixa', 'normal', 'alta', 'urgente'])->default('normal');
            $table->text('diagnostico');
            $table->text('orientacao');
            $table->foreignId('produto_id')->nullable()->constrained('produtos')->nullOnDelete();
            $table->decimal('dose', 10, 3)->nullable();
            $table->enum('status', ['pendente', 'tarefa_criada', 'recusada'])->default('pendente');
            $table->text('parecer_admin')->nullable();
            $table->timestamp('analisado_em')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recomendacoes');
    }
};
