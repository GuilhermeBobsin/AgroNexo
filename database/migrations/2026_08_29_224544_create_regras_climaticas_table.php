<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('regras_climaticas', function (Blueprint $table) {
            $table->id();

            $table->string('nome');
            $table->string('variavel');

            $table->enum('operador', [
                'maior',
                'maior_igual',
                'menor',
                'menor_igual',
                'igual',
            ]);

            $table->decimal('valor', 10, 2);

            $table->enum('gravidade', [
                'baixa',
                'media',
                'alta',
            ])->default('media');

            $table->text('mensagem');
            $table->boolean('ativa')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('regras_climaticas');
    }
};
