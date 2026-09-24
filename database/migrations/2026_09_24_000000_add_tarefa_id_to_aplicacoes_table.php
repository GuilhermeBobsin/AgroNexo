<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('aplicacoes', function (Blueprint $table) {
            $table->foreignId('tarefa_id')->nullable()->after('id')
                ->constrained('tarefas')->nullOnDelete();
            $table->unique('tarefa_id');
        });
    }

    public function down(): void
    {
        Schema::table('aplicacoes', function (Blueprint $table) {
            $table->dropUnique(['tarefa_id']);
            $table->dropConstrainedForeignId('tarefa_id');
        });
    }
};
