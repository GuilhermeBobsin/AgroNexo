<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propriedade_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propriedade_id')->constrained('propriedades')->cascadeOnDelete();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->enum('papel', ['dono', 'colaborador', 'agronomo'])->default('colaborador');
            $table->timestamps();
            $table->unique(['propriedade_id', 'usuario_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propriedade_usuario');
    }
};
