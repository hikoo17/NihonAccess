<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\SubmitWritingRequest;
use App\Http\Resources\WritingAttemptResource;
use App\Http\Resources\WritingExerciseResource;
use App\Models\WritingAttempt;
use App\Models\WritingExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientWritingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $exercises = WritingExercise::query()
            ->active()
            ->when($request->filled('type'), fn ($query) => $query->type($request->string('type')->toString()))
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Latihan tulis huruf berhasil diambil.',
            'data' => WritingExerciseResource::collection($exercises),
            'meta' => $this->paginationMeta($exercises),
        ]);
    }

    public function submit(SubmitWritingRequest $request): JsonResponse
    {
        $attempt = DB::transaction(function () use ($request): WritingAttempt {
            $validated = $request->validated();
            $exercise = WritingExercise::query()->active()->findOrFail($validated['writing_exercise_id']);

            return WritingAttempt::query()->create([
                'user_id' => $request->user()->id,
                'writing_exercise_id' => $exercise->id,
                'submission_image' => $validated['submission_image'] ?? null,
                'metadata' => $validated['metadata'] ?? null,
                'score' => $validated['score'] ?? null,
                'submitted_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Hasil tulisan berhasil dikirim.',
            'data' => new WritingAttemptResource($attempt->load('exercise')),
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
