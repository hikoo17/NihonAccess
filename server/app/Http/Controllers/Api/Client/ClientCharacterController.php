<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\SubmitCharacterRequest;
use App\Models\CharacterAttempt;
use App\Models\CharacterExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientCharacterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $exercises = CharacterExercise::query()
            ->active()
            ->when($request->filled('type'), fn ($query) => $query->type($request->string('type')->toString()))
            ->when($request->filled('course_id'), fn ($query) => $query->where('course_id', $request->integer('course_id')))
            ->when($request->filled('lesson_id'), fn ($query) => $query->where('lesson_id', $request->integer('lesson_id')))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Latihan tebak huruf berhasil diambil.',
            'data' => $exercises->through(fn (CharacterExercise $e) => $this->publicArray($e)),
            'meta' => $this->paginationMeta($exercises),
        ]);
    }

    public function show(Request $request, CharacterExercise $characterExercise): JsonResponse
    {
        abort_if(! $characterExercise->is_active || ! $characterExercise->course?->is_active, 404);

        return response()->json([
            'success' => true,
            'message' => 'Detail latihan tebak huruf berhasil diambil.',
            'data' => $this->publicArray($characterExercise),
        ]);
    }

    public function submit(SubmitCharacterRequest $request): JsonResponse
    {
        $attempt = DB::transaction(function () use ($request): CharacterAttempt {
            $validated = $request->validated();
            $exercise = CharacterExercise::query()->active()->findOrFail($validated['character_exercise_id']);

            $isCorrect = strcasecmp(trim($validated['answer']), trim($exercise->answer)) === 0;

            return CharacterAttempt::query()->create([
                'user_id' => $request->user()->id,
                'character_exercise_id' => $exercise->id,
                'answer' => $validated['answer'],
                'is_correct' => $isCorrect,
                'submitted_at' => now(),
            ]);
        });

        // Beri feedback setelah submit: benar/salah + jawaban benar (siswa sudah menjawab)
        return response()->json([
            'success' => true,
            'message' => 'Jawaban tebak huruf berhasil dikirim.',
            'data' => [
                'id' => $attempt->id,
                'is_correct' => $attempt->is_correct,
                'user_answer' => $attempt->answer,
                'correct_answer' => $attempt->exercise->answer,
            ],
        ], 201);
    }

    private function publicArray(CharacterExercise $e): array
    {
        return [
            'id'             => $e->id,
            'course_id'      => $e->course_id,
            'lesson_id'      => $e->lesson_id,
            'character_type' => $e->character_type,
            'character'      => $e->character,
            'options'        => $e->options,
            'hint'           => $e->hint,
            'is_active'      => $e->is_active,
        ];
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
