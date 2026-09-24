<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tarefas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('propriedade_id')->constrained('propriedades')->cascadeOnDelete();
            $table->foreignId('talhao_id')->nullable()->constrained('talhoes')->nullOnDelete();

            $table->enum('tipo', ['aplicacao', 'aracao', 'calagem', 'irrigacao', 'manutencao', 'outro']);
            $table->string('titulo');

            $table->foreignId('responsavel_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('recurso_id')->nullable()->constrained('recursos')->nullOnDelete();

            // Só preenchidos quando tipo = aplicacao
            $table->foreignId('produto_id')->nullable()->constrained('produtos')->restrictOnDelete();
            $table->decimal('dose', 10, 3)->nullable();

            $table->enum('status', ['pendente', 'em_andamento', 'concluida', 'cancelada'])->default('pendente');
            $table->date('data_prevista');
            $table->time('hora_prevista')->nullable();
            $table->dateTime('data_conclusao')->nullable();
            $table->text('observacoes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarefas');
    }
};
