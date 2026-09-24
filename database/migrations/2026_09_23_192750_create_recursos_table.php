<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recursos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propriedade_id')->constrained('propriedades')->cascadeOnDelete();
            $table->string('nome');
            $table->enum('tipo', ['trator', 'implemento', 'pulverizador', 'colheitadeira', 'outro'])->default('outro');
            $table->enum('status', ['disponivel', 'em_uso', 'manutencao'])->default('disponivel');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recursos');
    }
};
