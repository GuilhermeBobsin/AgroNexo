<?php

namespace App\Console\Commands;

use App\Models\Propriedade;
use App\Jobs\SincronizarPrevisaoPropriedade;
use Illuminate\Console\Command;

class AtualizarPrevisoesClimaticas extends Command
{
    protected $signature = 'clima:atualizar-previsoes';
    protected $description = 'Atualiza as previsões meteorológicas dos talhões pela Open-Meteo';

    public function handle(): int
    {
        $falhas = 0;
        $propriedades = Propriedade::whereHas('talhoes')->with('talhoes')->get();
        if ($propriedades->isEmpty()) {
            $this->warn('Não há propriedades com talhões cadastrados para atualizar.');
            return self::SUCCESS;
        }
        foreach ($propriedades as $propriedade) {
            if ($propriedade->latitude === null || $propriedade->longitude === null) {
                $falhas++;
                $this->error("{$propriedade->nome}: informe latitude e longitude para sincronizar o clima.");
                continue;
            }
            SincronizarPrevisaoPropriedade::dispatch($propriedade->id);
            $this->info("{$propriedade->nome}: sincronização enviada para a fila.");
        }

        return $falhas ? self::FAILURE : self::SUCCESS;
    }
}
