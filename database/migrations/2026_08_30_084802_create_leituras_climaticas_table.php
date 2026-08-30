<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leituras_climaticas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('talhao_id')
                ->constrained('talhoes')
                ->cascadeOnDelete();

            $table->decimal('temperatura', 5, 2)->nullable();
            $table->decimal('umidade', 5, 2)->nullable();
            $table->decimal('velocidade_vento', 6, 2)->nullable();
            $table->decimal('precipitacao', 8, 2)->nullable();
            $table->decimal('chance_chuva', 5, 2)->nullable();

            $table->dateTime('coletado_em');

            $table->timestamps();
            $table->index(['talhao_id', 'coletado_em']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leituras_climaticas');
    }
};
