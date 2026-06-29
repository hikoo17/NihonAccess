<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\StoreQuizRequest;
use App\Http\Requests\Teacher\UpdateQuizRequest;
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
            ->whereHas('course', fn ($query) => $query->where('teacher_id', $request->user()->id))
            ->with(['course', 'lesson', 'questions'])
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Daftar quiz berhasil diambil.',
            'data'    => QuizResource::collection($quizzes),
            'meta'    => $this->paginationMeta($quizzes),s
        ]);
    }
    public function show(Request $request, Quiz $quiz): JsonResponse
    {
        $this->ensureOwnsQuiz($request, $quiz);

        return response()->json([
            'success' => true,
            'message' => 'Detail quiz berhasil diambil.',
            'data' => new QuizResource($quiz->load(['course', 'lesson', 'questions'])),
        ]);
    }

    public function store(StoreQuizRequest $request): JsonResponse
    {
        $validated = $request->validated();
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
            'data' => new QuizResource($quiz->load(['course', 'lesson', 'questions'])),
        ], 201);
    }

    public function update(UpdateQuizRequest $request, Quiz $quiz): JsonResponse
    {
        $this->ensureOwnsQuiz($request, $quiz);
        $validated = $request->validated();

        if (isset($validated['course_id'])) {
            Course::query()->where('teacher_id', $request->user()->id)->findOrFail($validated['course_id']);
        }

        DB::transaction(function () use ($quiz, $validated): void {
            $questions = $validated['questions'] ?? null;
            unset($validated['questions']);

            $quiz->update($validated);

            if (is_array($questions)) {
                $quiz->questions()->delete();
                $quiz->questions()->createMany($questions);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil diperbarui.',
            'data' => new QuizResource($quiz->refresh()->load(['course', 'lesson', 'questions'])),
        ]);
    }

    public function destroy(Request $request, Quiz $quiz): JsonResponse
    {
        $this->ensureOwnsQuiz($request, $quiz);

        DB::transaction(fn () => $quiz->delete());

        return response()->json(['success' => true, 'message' => 'Quiz berhasil dihapus.', 'data' => null]);
    }

    private function ensureOwnsQuiz(Request $request, Quiz $quiz): void
    {
        abort_if($quiz->course()->where('teacher_id', $request->user()->id)->doesntExist(), 403);
    }

    private function paginatedResponse(string $message, $paginator): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => QuizResource::collection($paginator),
            'meta' => $this->paginationMeta($paginator),
        ]);
    }

    private function paginationMeta($paginator): array
    {
        return ['current_page' => $paginator->currentPage(), 'last_page' => $paginator->lastPage(), 'per_page' => $paginator->perPage(), 'total' => $paginator->total()];
    }
}
