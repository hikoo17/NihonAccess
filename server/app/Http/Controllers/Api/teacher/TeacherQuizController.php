<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Resources\QuizResource;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherQuizController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $quizzes = Quiz::query()
            ->whereHas('course', fn ($q) => $q->where('teacher_id', $request->user()->id))
            ->with(['course', 'lesson', 'questions'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return $this->paginatedResponse('Daftar quiz berhasil diambil.', $quizzes, QuizResource::class);
    }

    public function show(Request $request, Quiz $quiz): JsonResponse
    {
        $this->ensureOwns($request, $quiz);

        return response()->json([
            'success' => true,
            'message' => 'Detail quiz berhasil diambil.',
            'data'    => new QuizResource($quiz->load(['course', 'lesson', 'questions'])),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id'                  => ['required', 'integer', 'exists:courses,id'],
            'lesson_id'                  => ['nullable', 'integer', 'exists:lessons,id'],
            'title'                      => ['required', 'string', 'max:255'],
            'description'                => ['nullable', 'string'],
            'passing_score'              => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_active'                  => ['nullable', 'boolean'],
            'questions'                  => ['nullable', 'array'],
            'questions.*.question'       => ['required', 'string'],
            'questions.*.options'        => ['required', 'array'],
            'questions.*.correct_answer' => ['required', 'string'],
            'questions.*.explanation'    => ['nullable', 'string'],
            'questions.*.sort_order'     => ['nullable', 'integer', 'min:0'],
        ]);

        Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);

        $quiz = DB::transaction(function () use ($validated): Quiz {
            $questions = $validated['questions'] ?? [];
            unset($validated['questions']);

            $quiz = Quiz::query()->create($validated);
            $quiz->questions()->createMany($questions);

            return $quiz;
        });

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil dibuat.',
            'data'    => new QuizResource($quiz->load(['course', 'lesson', 'questions'])),
        ], 201);
    }

    public function update(Request $request, Quiz $quiz): JsonResponse
    {
        $this->ensureOwns($request, $quiz);

        $validated = $request->validate([
            'course_id'                  => ['sometimes', 'integer', 'exists:courses,id'],
            'lesson_id'                  => ['nullable', 'integer', 'exists:lessons,id'],
            'title'                      => ['sometimes', 'string', 'max:255'],
            'description'                => ['nullable', 'string'],
            'passing_score'              => ['nullable', 'integer', 'min:0', 'max:100'],
            'is_active'                  => ['nullable', 'boolean'],
            'questions'                  => ['nullable', 'array'],
            'questions.*.question'       => ['required', 'string'],
            'questions.*.options'        => ['required', 'array'],
            'questions.*.correct_answer' => ['required', 'string'],
            'questions.*.explanation'    => ['nullable', 'string'],
            'questions.*.sort_order'     => ['nullable', 'integer', 'min:0'],
        ]);

        if (isset($validated['course_id'])) {
            Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);
        }

        DB::transaction(function () use ($quiz, $validated): void {
            $questions = $validated['questions'] ?? null;
            unset($validated['questions']);

            $quiz->update($validated);

            // Hanya replace soal kalau questions dikirim
            if (is_array($questions)) {
                $quiz->questions()->delete();
                $quiz->questions()->createMany($questions);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil diperbarui.',
            'data'    => new QuizResource($quiz->refresh()->load(['course', 'lesson', 'questions'])),
        ]);
    }

    public function destroy(Request $request, Quiz $quiz): JsonResponse
    {
        $this->ensureOwns($request, $quiz);

        DB::transaction(fn () => $quiz->delete());

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil dihapus.',
            'data'    => null,
        ]);
    }

    private function ensureOwns(Request $request, Quiz $quiz): void
    {
        abort_if(
            $quiz->course()->where('teacher_id', $request->user()->id)->doesntExist(),
            403,
            'Akses ditolak.'
        );
    }
}