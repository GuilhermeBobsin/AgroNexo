<?php

namespace App\Jobs;

use App\Models\Propriedade;
use App\Services\Clima\OpenMeteoService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class SincronizarPrevisaoPropriedade implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 4;

    public int $timeout = 45;

    public int $uniqueFor = 3600;

    public function __construct(public int $propriedadeId)
    {
    }

    public function uniqueId(): string
    {
        return 'clima-propriedade-'.$this->propriedadeId;
    }

    public function backoff(): array
    {
        return [30, 120, 300];
    }

    public function handle(OpenMeteoService $clima): void
    {
        $propriedade = Propriedade::with('talhoes')->findOrFail($this->propriedadeId);
        if ($propriedade->talhoes->isEmpty()) {
            return;
        }

        $total = $clima->atualizarPropriedade($propriedade);
        Log::info('Previsão climática atualizada.', [
            'propriedade_id' => $propriedade->id,
            'talhoes' => $propriedade->talhoes->count(),
            'horarios_atualizados' => $total,
        ]);
    }

    public function failed(?Throwable $exception): void
    {
        Log::error('Falha definitiva ao sincronizar previsão climática.', [
            'propriedade_id' => $this->propriedadeId,
            'erro' => $exception?->getMessage(),
        ]);
    }
}
