<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\SubmitListeningRequest;
use App\Http\Resources\ListeningAttemptResource;
use App\Http\Resources\ListeningExerciseResource;
use App\Models\ListeningAttempt;
use App\Models\ListeningExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientListeningController extends Controller
{
    public function start(ListeningExercise $listeningExercise): JsonResponse
    {
        abort_if(! $listeningExercise->is_active || ! $listeningExercise->course?->is_active, 404);

        return response()->json([
            'success' => true,
            'message' => 'Listening exercise berhasil dimulai.',
            'data' => new ListeningExerciseResource($listeningExercise->load('questions')),
        ]);
    }

    public function submit(SubmitListeningRequest $request, ListeningExercise $listeningExercise): JsonResponse
    {
        abort_if(! $listeningExercise->is_active || ! $listeningExercise->course?->is_active, 404);

        $attempt = DB::transaction(function () use ($request, $listeningExercise): ListeningAttempt {
            $questions = $listeningExercise->questions()->get()->keyBy('id');
            $answers = collect($request->validated('answers'));
            $correctCount = $answers->filter(fn (array $answer): bool => ($questions[$answer['question_id']]->correct_answer ?? null) === $answer['answer'])->count();
            $score = $questions->isNotEmpty() ? (int) round(($correctCount / $questions->count()) * 100) : 0;

            return ListeningAttempt::query()->create([
                'user_id' => $request->user()->id,
                'listening_exercise_id' => $listeningExercise->id,
                'answers' => $answers->values()->all(),
                'score' => $score,
                'is_passed' => $score >= 70,
                'submitted_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Jawaban listening berhasil dikirim.',
            'data' => new ListeningAttemptResource($attempt->load('exercise')),
        ], 201);
    }

    public function history(Request $request): JsonResponse
    {
        $attempts = ListeningAttempt::query()
            ->where('user_id', $request->user()->id)
            ->with('exercise')
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Riwayat listening berhasil diambil.',
            'data' => ListeningAttemptResource::collection($attempts),
            'meta' => $this->paginationMeta($attempts),
        ]);
    }

    private function paginationMeta($paginator): array
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }
}
