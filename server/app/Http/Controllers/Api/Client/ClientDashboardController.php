<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\EnrollmentResource;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientDashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $enrollments = $user->activeEnrollments()->with('package')->get();

        // Total lessons yang dapat diakses user (dari semua course di semua package aktif)
        $enrolledPackageIds = $enrollments->pluck('package_id');
        $totalLessons = Lesson::query()
            ->active()
            ->whereHas('course', fn ($q) => $q
                ->whereHas('packages', fn ($pq) => $pq->whereIn('packages.id', $enrolledPackageIds)))
            ->count();

        $completedLessons = LessonProgress::query()
            ->where('user_id', $user->id)
            ->completed()
            ->count();

        // Hari aktif bulan ini (jumlah hari unik ada aktivitas lesson_progress)
        $activeDays = LessonProgress::query()
            ->where('user_id', $user->id)
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->distinct()
            ->count(DB::raw('DATE(updated_at)'));

        return response()->json([
            'success' => true,
            'message' => 'Dashboard client berhasil diambil.',
            'data' => [
                'user' => [
                    'name' => $user->name,
                ],
                'stats' => [
                    'active_courses'   => $enrollments->count(),
                    'progress_percent' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100, 2) : 0,
                    'completed_lessons' => $completedLessons,
                    'total_lessons'     => $totalLessons,
                    'active_days'       => $activeDays,
                ],
                'enrollments' => EnrollmentResource::collection($enrollments),
            ],
        ]);
    }
}