<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produto_propriedade', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            $table->foreignId('propriedade_id')->constrained('propriedades')->cascadeOnDelete();
            $table->decimal('estoque_atual', 12, 3)->default(0);
            $table->decimal('estoque_minimo', 12, 3)->default(0);
            $table->date('data_validade')->nullable();
            $table->timestamps();
            $table->unique(['produto_id', 'propriedade_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produto_propriedade');
    }
};
