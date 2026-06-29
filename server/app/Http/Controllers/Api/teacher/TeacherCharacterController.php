<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreCharacterRequest;
use App\Http\Requests\Teacher\UpdateCharacterRequest;
use App\Http\Resources\CharacterExerciseResource;
use App\Models\CharacterExercise;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherCharacterController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $exercises = CharacterExercise::query()
            ->whereHas('course', fn ($query) => $query->where('teacher_id', $request->user()->id))
            ->with(['course', 'lesson'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json(['success' => true, 'message' => 'Daftar character exercise berhasil diambil.', 'data' => CharacterExerciseResource::collection($exercises), 'meta' => $this->paginationMeta($exercises)]);
    }

    public function show(Request $request, CharacterExercise $characterExercise): JsonResponse
    {
        $this->ensureOwnsExercise($request, $characterExercise);

        return response()->json(['success' => true, 'message' => 'Detail character exercise berhasil diambil.', 'data' => new CharacterExerciseResource($characterExercise->load(['course', 'lesson']))]);
    }

    public function store(StoreCharacterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);

        $exercise = DB::transaction(fn (): CharacterExercise => CharacterExercise::query()->create($validated));

        return response()->json(['success' => true, 'message' => 'Character exercise berhasil dibuat.', 'data' => new CharacterExerciseResource($exercise->load(['course', 'lesson']))], 201);
    }

    public function update(UpdateCharacterRequest $request, CharacterExercise $characterExercise): JsonResponse
    {
        $this->ensureOwnsExercise($request, $characterExercise);
        $validated = $request->validated();

        if (isset($validated['course_id'])) {
            Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);
        }

        DB::transaction(fn () => $characterExercise->update($validated));

        return response()->json(['success' => true, 'message' => 'Character exercise berhasil diperbarui.', 'data' => new CharacterExerciseResource($characterExercise->refresh()->load(['course', 'lesson']))]);
    }

    public function destroy(Request $request, CharacterExercise $characterExercise): JsonResponse
    {
        $this->ensureOwnsExercise($request, $characterExercise);

        DB::transaction(fn () => $characterExercise->delete());

        return response()->json(['success' => true, 'message' => 'Character exercise berhasil dihapus.', 'data' => null]);
    }

    private function ensureOwnsExercise(Request $request, CharacterExercise $exercise): void
    {
        abort_if($exercise->course()->where('teacher_id', $request->user()->id)->doesntExist(), 403);
    }

    private function paginationMeta($paginator): array
    {
        return ['current_page' => $paginator->currentPage(), 'last_page' => $paginator->lastPage(), 'per_page' => $paginator->perPage(), 'total' => $paginator->total()];
    }
}
