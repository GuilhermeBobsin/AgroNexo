<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('propriedades', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('responsavel')->nullable();
            $table->string('localizacao')->nullable();
            $table->string('latitude', 10, 7)->nullable();
            $table->string('longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('propriedades');
    }
};
