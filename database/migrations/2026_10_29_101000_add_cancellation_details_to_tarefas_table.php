<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            $table->dateTime('data_cancelamento')->nullable()->after('data_conclusao');
            $table->text('motivo_cancelamento')->nullable()->after('data_cancelamento');
        });
    }

    public function down(): void
    {
        Schema::table('tarefas', function (Blueprint $table) {
            $table->dropColumn(['data_cancelamento', 'motivo_cancelamento']);
        });
    }
};
