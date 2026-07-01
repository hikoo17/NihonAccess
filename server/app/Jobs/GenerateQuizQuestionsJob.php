<?php

namespace App\Jobs;

use App\Models\AiGeneration;
use App\Services\AI\GeminiService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateQuizQuestionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1; // jangan retry otomatis (boros API call)

    public function __construct(
        public int $generationId,
    ) {}

    public function handle(GeminiService $gemini): void
    {
        $gen = AiGeneration::find($this->generationId);

        if (! $gen || $gen->status === 'completed') {
            return;
        }

        try {
            $gen->update(['status' => 'processing']);

            $input  = $gen->input ?? [];
            $result = $gemini->generateQuizFromVideo(
                (string) ($input['video_url'] ?? ''),
                (int) ($input['count'] ?? 5),
            );

            $gen->update([
                'status'        => 'completed',
                'result'        => $result,
                'error_message' => null,
            ]);
        } catch (Throwable $e) {
            $gen->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
        }
    }
}