<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
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
            ->whereHas('course', fn ($q) => $q->where('teacher_id', $request->user()->id))
            ->with(['course', 'lesson', 'questions'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return $this->paginatedResponse('Daftar listening exercise berhasil diambil.', $exercises, ListeningExerciseResource::class);
    }

    public function show(Request $request, ListeningExercise $listeningExercise): JsonResponse
    {
        $this->ensureOwns($request, $listeningExercise);

        return response()->json([
            'success' => true,
            'message' => 'Detail listening exercise berhasil diambil.',
            'data'    => new ListeningExerciseResource($listeningExercise->load(['course', 'lesson', 'questions'])),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id'                  => ['required', 'integer', 'exists:courses,id'],
            'lesson_id'                  => ['nullable', 'integer', 'exists:lessons,id'],
            'title'                      => ['required', 'string', 'max:255'],
            'description'                => ['nullable', 'string'],
            'audio_url'                  => ['required', 'url', 'max:500'],
            'is_active'                  => ['nullable', 'boolean'],
            'questions'                  => ['nullable', 'array'],
            'questions.*.question'       => ['required', 'string'],
            'questions.*.options'        => ['nullable', 'array'],
            'questions.*.correct_answer' => ['required', 'string'],
            'questions.*.sort_order'     => ['nullable', 'integer', 'min:0'],
        ]);

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
            'data'    => new ListeningExerciseResource($exercise->load(['course', 'lesson', 'questions'])),
        ], 201);
    }

    public function update(Request $request, ListeningExercise $listeningExercise): JsonResponse
    {
        $this->ensureOwns($request, $listeningExercise);

        $validated = $request->validate([
            'course_id'                  => ['sometimes', 'integer', 'exists:courses,id'],
            'lesson_id'                  => ['nullable', 'integer', 'exists:lessons,id'],
            'title'                      => ['sometimes', 'string', 'max:255'],
            'description'                => ['nullable', 'string'],
            'audio_url'                  => ['sometimes', 'url', 'max:500'],
            'is_active'                  => ['nullable', 'boolean'],
            'questions'                  => ['nullable', 'array'],
            'questions.*.question'       => ['required', 'string'],
            'questions.*.options'        => ['nullable', 'array'],
            'questions.*.correct_answer' => ['required', 'string'],
            'questions.*.sort_order'     => ['nullable', 'integer', 'min:0'],
        ]);

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
            'data'    => new ListeningExerciseResource($listeningExercise->refresh()->load(['course', 'lesson', 'questions'])),
        ]);
    }

    public function destroy(Request $request, ListeningExercise $listeningExercise): JsonResponse
    {
        $this->ensureOwns($request, $listeningExercise);

        DB::transaction(fn () => $listeningExercise->delete());

        return response()->json([
            'success' => true,
            'message' => 'Listening exercise berhasil dihapus.',
            'data'    => null,
        ]);
    }

    private function ensureOwns(Request $request, ListeningExercise $exercise): void
    {
        abort_if(
            $exercise->course()->where('teacher_id', $request->user()->id)->doesntExist(),
            403,
            'Akses ditolak.'
        );
    }
}