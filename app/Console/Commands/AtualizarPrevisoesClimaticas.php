<?php

namespace App\Console\Commands;

use App\Models\Propriedade;
use App\Services\Clima\OpenMeteoService;
use Illuminate\Console\Command;
use Throwable;

class AtualizarPrevisoesClimaticas extends Command
{
    protected $signature = 'clima:atualizar-previsoes';
    protected $description = 'Atualiza as previsões meteorológicas dos talhões pela Open-Meteo';

    public function handle(OpenMeteoService $clima): int
    {
        $falhas = 0;
        $propriedades = Propriedade::whereNotNull('latitude')->whereNotNull('longitude')->with('talhoes')->get();
        foreach ($propriedades as $propriedade) {
            if ($propriedade->talhoes->isEmpty()) {
                continue;
            }
            try {
                $total = $clima->atualizarPropriedade($propriedade);
                $this->info("{$propriedade->nome}: {$total} horários-talhão atualizados.");
            } catch (Throwable $erro) {
                $falhas++;
                report($erro);
                $this->error("{$propriedade->nome}: {$erro->getMessage()}");
            }
        }

        return $falhas ? self::FAILURE : self::SUCCESS;
    }
}
