<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movimentacoes_estoque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_propriedade_id')->constrained('produto_propriedade')->restrictOnDelete();
            $table->foreignId('tarefa_id')->nullable()->constrained('tarefas')->nullOnDelete();
            $table->foreignId('usuario_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('tipo', ['entrada', 'saida', 'ajuste']);
            $table->decimal('quantidade', 12, 3);
            $table->decimal('saldo_anterior', 12, 3);
            $table->decimal('saldo_atual', 12, 3);
            $table->string('motivo', 255);
            $table->timestamps();
            $table->index(['produto_propriedade_id', 'created_at'], 'mov_estoque_produto_data_idx');
            $table->unique('tarefa_id');
        });

        DB::table('produto_propriedade')->orderBy('id')->chunkById(200, function ($registros) {
            foreach ($registros as $registro) {
                if ((float) $registro->estoque_atual <= 0) {
                    continue;
                }
                DB::table('movimentacoes_estoque')->insert([
                    'produto_propriedade_id' => $registro->id,
                    'tipo' => 'entrada',
                    'quantidade' => $registro->estoque_atual,
                    'saldo_anterior' => 0,
                    'saldo_atual' => $registro->estoque_atual,
                    'motivo' => 'Saldo inicial existente; histórico anterior à implantação indisponível',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_estoque');
    }
};
