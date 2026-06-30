<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\SubmitCharacterRequest;
use App\Http\Resources\CharacterAttemptResource;
use App\Http\Resources\CharacterExerciseResource;
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
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Latihan tebak huruf berhasil diambil.',
            'data' => CharacterExerciseResource::collection($exercises),
            'meta' => $this->paginationMeta($exercises),
        ]);
    }

    public function submit(SubmitCharacterRequest $request): JsonResponse
    {
        $attempt = DB::transaction(function () use ($request): CharacterAttempt {
            $validated = $request->validated();
            $exercise = CharacterExercise::query()->active()->findOrFail($validated['character_exercise_id']);

            return CharacterAttempt::query()->create([
                'user_id' => $request->user()->id,
                'character_exercise_id' => $exercise->id,
                'answer' => $validated['answer'],
                'is_correct' => strcasecmp(trim($validated['answer']), trim($exercise->answer)) === 0,
                'submitted_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Jawaban tebak huruf berhasil dikirim.',
            'data' => new CharacterAttemptResource($attempt->load('exercise')),
        ], 201);
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
