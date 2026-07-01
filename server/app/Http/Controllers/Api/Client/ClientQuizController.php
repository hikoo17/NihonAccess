<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\SubmitQuizRequest;
use App\Http\Resources\QuizAttemptResource;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientQuizController extends Controller
{

        /**
     * Daftar quiz yang bisa diakses user (hanya dari kursus yang ia enroll),
     * beserta skor terbaik yang pernah diraih.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // ID course yang bisa diakses user (via enrollment -> package -> course)
        $courseIds = Course::query()
            ->active()
            ->whereHas('packages', fn ($q) =>
                $q->whereIn('packages.id', $user->enrollments()->pluck('package_id')))
            ->pluck('id');

        $quizzes = Quiz::query()
            ->active()
            ->whereIn('course_id', $courseIds)
            ->with(['course:id,title,slug'])
            ->withCount('questions')
            ->addSelect([
                'best_score' => QuizAttempt::query()
                    ->select('score')
                    ->whereColumn('quiz_id', 'quizzes.id')
                    ->where('user_id', $user->id)
                    ->orderByDesc('score')
                    ->limit(1),
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar quiz berhasil diambil.',
            'data' => $quizzes->map(fn ($q) => [
                'id'              => $q->id,
                'title'           => $q->title,
                'description'     => $q->description,
                'passing_score'   => $q->passing_score,
                'course'          => $q->course,
                'questions_count' => $q->questions_count,
                'best_score'      => $q->best_score,
                'has_passed'      => $q->best_score !== null && $q->best_score >= $q->passing_score,
            ]),
        ]);
    }


    public function start(Quiz $quiz): JsonResponse
    {
        abort_if(! $quiz->is_active || ! $quiz->course?->is_active, 404);

        return response()->json([
            'success' => true,
            'message' => 'Quiz berhasil dimulai.',
            'data' => new QuizResource($quiz->load('questions')),
        ]);
    }
    

    public function submit(SubmitQuizRequest $request, Quiz $quiz): JsonResponse
    {
        abort_if(! $quiz->is_active || ! $quiz->course?->is_active, 404);

        $attempt = DB::transaction(function () use ($request, $quiz): QuizAttempt {
            $questions = $quiz->questions()->get()->keyBy('id');
            $answers = collect($request->validated('answers'));
            $correctCount = $answers->filter(fn (array $answer): bool => ($questions[$answer['question_id']]->correct_answer ?? null) === $answer['answer'])->count();
            $score = $questions->isNotEmpty() ? (int) round(($correctCount / $questions->count()) * 100) : 0;

            return QuizAttempt::query()->create([
                'user_id' => $request->user()->id,
                'quiz_id' => $quiz->id,
                'answers' => $answers->values()->all(),
                'score' => $score,
                'is_passed' => $score >= $quiz->passing_score,
                'started_at' => now(),
                'submitted_at' => now(),
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Jawaban quiz berhasil dikirim.',
            'data' => new QuizAttemptResource($attempt->load(['quiz.course', 'quiz.questions'])),
        ], 201);
    }

        public function show(Request $request, QuizAttempt $attempt): JsonResponse
    {
        abort_if($attempt->user_id !== $request->user()->id, 403, 'Akses ditolak.');

        return response()->json([
            'success' => true,
            'message' => 'Detail attempt berhasil diambil.',
            'data'    => new QuizAttemptResource($attempt->load(['quiz.course', 'quiz.questions'])),
        ]);
    }

    public function history(Request $request): JsonResponse
    {
        $attempts = QuizAttempt::query()
            ->where('user_id', $request->user()->id)
            ->with('quiz')
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Riwayat quiz berhasil diambil.',
            'data' => QuizAttemptResource::collection($attempts),
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
