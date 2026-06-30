<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollmentResource;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientDashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $totalLessons = Lesson::query()->active()->count();
        $completedLessons = LessonProgress::query()
            ->where('user_id', $user->id)
            ->completed()
            ->count();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard client berhasil diambil.',
            'data' => [
                'enrollments' => EnrollmentResource::collection($user->activeEnrollments()->with('package')->get()),
                'progress' => [
                    'total_lessons' => $totalLessons,
                    'completed_lessons' => $completedLessons,
                    'percentage' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0,
                ],
            ],
        ]);
    }
}
