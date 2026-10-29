<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('previsoes_climaticas', function (Blueprint $table) {
            $table->decimal('evapotranspiracao', 8, 3)->nullable()->after('umidade_solo');
        });
    }

    public function down(): void
    {
        Schema::table('previsoes_climaticas', function (Blueprint $table) {
            $table->dropColumn('evapotranspiracao');
        });
    }
};
