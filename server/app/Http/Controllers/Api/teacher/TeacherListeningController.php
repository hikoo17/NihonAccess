<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreListeningRequest;
use App\Http\Requests\Teacher\UpdateListeningRequest;
use App\Http\Resources\ListeningExerciseResource;
use App\Models\Course;
use App\Models\ListeningExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherListeningController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $exercises = ListeningExercise::query()
            ->whereHas('course', fn ($query) => $query->where('teacher_id', $request->user()->id))
            ->with(['course', 'lesson', 'questions'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar listening exercise berhasil diambil.',
            'data' => ListeningExerciseResource::collection($exercises),
            'meta' => $this->paginationMeta($exercises),
        ]);
    }

    public function show(Request $request, ListeningExercise $listeningExercise): JsonResponse
    {
        $this->ensureOwnsExercise($request, $listeningExercise);

        return response()->json([
            'success' => true,
            'message' => 'Detail listening exercise berhasil diambil.',
            'data' => new ListeningExerciseResource($listeningExercise->load(['course', 'lesson', 'questions'])),
        ]);
    }

    public function store(StoreListeningRequest $request): JsonResponse
    {
        $validated = $request->validated();
        Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);

        $exercise = DB::transaction(function () use ($validated): ListeningExercise {
            $questions = $validated['questions'] ?? [];
            unset($validated['questions']);

            $exercise = ListeningExercise::query()->create($validated);
            $exercise->questions()->createMany($questions);

            return $exercise;
        });

        return response()->json([
            'success' => true,
            'message' => 'Listening exercise berhasil dibuat.',
            'data' => new ListeningExerciseResource($exercise->load(['course', 'lesson', 'questions'])),
        ], 201);
    }

    public function update(UpdateListeningRequest $request, ListeningExercise $listeningExercise): JsonResponse
    {
        $this->ensureOwnsExercise($request, $listeningExercise);
        $validated = $request->validated();

        if (isset($validated['course_id'])) {
            Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);
        }

        DB::transaction(function () use ($listeningExercise, $validated): void {
            $questions = $validated['questions'] ?? null;
            unset($validated['questions']);

            $listeningExercise->update($validated);

            if (is_array($questions)) {
                $listeningExercise->questions()->delete();
                $listeningExercise->questions()->createMany($questions);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Listening exercise berhasil diperbarui.',
            'data' => new ListeningExerciseResource($listeningExercise->refresh()->load(['course', 'lesson', 'questions'])),
        ]);
    }

    public function destroy(Request $request, ListeningExercise $listeningExercise): JsonResponse
    {
        $this->ensureOwnsExercise($request, $listeningExercise);

        DB::transaction(fn () => $listeningExercise->delete());

        return response()->json(['success' => true, 'message' => 'Listening exercise berhasil dihapus.', 'data' => null]);
    }

    private function ensureOwnsExercise(Request $request, ListeningExercise $exercise): void
    {
        abort_if($exercise->course()->where('teacher_id', $request->user()->id)->doesntExist(), 403);
    }

    private function paginationMeta($paginator): array
    {
        return ['current_page' => $paginator->currentPage(), 'last_page' => $paginator->lastPage(), 'per_page' => $paginator->perPage(), 'total' => $paginator->total()];
    }
}
