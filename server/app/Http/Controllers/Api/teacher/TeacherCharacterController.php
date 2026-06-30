<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
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
            ->whereHas('course', fn ($q) => $q->where('teacher_id', $request->user()->id))
            ->with(['course', 'lesson'])
            ->when($request->filled('character_type'), fn ($q) => $q->where('character_type', $request->input('character_type')))
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return $this->paginatedResponse('Daftar character exercise berhasil diambil.', $exercises, CharacterExerciseResource::class);
    }

    public function show(Request $request, CharacterExercise $characterExercise): JsonResponse
    {
        $this->ensureOwns($request, $characterExercise);

        return response()->json([
            'success' => true,
            'message' => 'Detail character exercise berhasil diambil.',
            'data'    => new CharacterExerciseResource($characterExercise->load(['course', 'lesson'])),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id'      => ['required', 'integer', 'exists:courses,id'],
            'lesson_id'      => ['nullable', 'integer', 'exists:lessons,id'],
            'character_type' => ['required', 'in:hiragana,katakana,kanji'],
            'character'      => ['required', 'string', 'max:255'],
            'answer'         => ['required', 'string', 'max:255'],
            'hint'           => ['nullable', 'string', 'max:255'],
            'is_active'      => ['nullable', 'boolean'],
        ]);

        Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);

        $exercise = DB::transaction(fn (): CharacterExercise => CharacterExercise::query()->create($validated));

        return response()->json([
            'success' => true,
            'message' => 'Character exercise berhasil dibuat.',
            'data'    => new CharacterExerciseResource($exercise->load(['course', 'lesson'])),
        ], 201);
    }

    public function update(Request $request, CharacterExercise $characterExercise): JsonResponse
    {
        $this->ensureOwns($request, $characterExercise);

        $validated = $request->validate([
            'course_id'      => ['sometimes', 'integer', 'exists:courses,id'],
            'lesson_id'      => ['nullable', 'integer', 'exists:lessons,id'],
            'character_type' => ['sometimes', 'in:hiragana,katakana,kanji'],
            'character'      => ['sometimes', 'string', 'max:255'],
            'answer'         => ['sometimes', 'string', 'max:255'],
            'hint'           => ['nullable', 'string', 'max:255'],
            'is_active'      => ['nullable', 'boolean'],
        ]);

        if (isset($validated['course_id'])) {
            Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);
        }

        DB::transaction(fn () => $characterExercise->update($validated));

        return response()->json([
            'success' => true,
            'message' => 'Character exercise berhasil diperbarui.',
            'data'    => new CharacterExerciseResource($characterExercise->refresh()->load(['course', 'lesson'])),
        ]);
    }

    public function destroy(Request $request, CharacterExercise $characterExercise): JsonResponse
    {
        $this->ensureOwns($request, $characterExercise);

        DB::transaction(fn () => $characterExercise->delete());

        return response()->json([
            'success' => true,
            'message' => 'Character exercise berhasil dihapus.',
            'data'    => null,
        ]);
    }

    private function ensureOwns(Request $request, CharacterExercise $exercise): void
    {
        abort_if(
            $exercise->course()->where('teacher_id', $request->user()->id)->doesntExist(),
            403,
            'Akses ditolak.'
        );
    }
}