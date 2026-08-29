<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('talhoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('propriedade_id')->constrained('propriedades')->cascadeOnDelete();
            $table->foreignId('cultura_id')->nullable()->constrained('culturas')->nullOnDelete();
            $table->string('nome');
            $table->string('area', 10, 2)->nullable();
            $table->string('latitude', 10, 7)->nullable();
            $table->string('longitude', 10, 7)->nullable();
            $table->timestamps();
            $table->unique(['propriedade_id', 'nome']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('talhoes');
    }
};
