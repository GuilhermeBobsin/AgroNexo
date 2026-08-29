<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aplicacoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('talhao_id')
                ->constrained('talhoes')
                ->cascadeOnDelete();

            $table->foreignId('produto_id')
                ->constrained('produtos')
                ->restrictOnDelete();

            $table->foreignId('usuario_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->enum('status', [
                'planejada',
                'realizada',
                'cancelada',
            ])->default('planejada');

            $table->decimal('dose', 10, 3);
            $table->string('equipamento')->nullable();

            $table->date('data_aplicacao');
            $table->time('hora_aplicacao')->nullable();

            $table->decimal('temperatura', 5, 2)->nullable();
            $table->decimal('umidade', 5, 2)->nullable();
            $table->decimal('velocidade_vento', 6, 2)->nullable();
            $table->decimal('precipitacao', 8, 2)->nullable();

            $table->text('observacoes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aplicacoes');
    }
};
