<?php

namespace App\Http\Controllers\Api\Teacher;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateListeningQuestionsJob;
use App\Jobs\GenerateQuizQuestionsJob;
use App\Models\AiGeneration;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AiGenerationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $v = Validator::make($request->all(), [
            'type'      => ['required', 'in:quiz,listening'],
            'lesson_id' => ['nullable', 'integer', 'exists:lessons,id'],
            'audio_url' => ['nullable', 'url', 'max:500'],
            'count'     => ['nullable', 'integer', 'min:1', 'max:15'],
        ]);

        // Aturan kondisional: quiz wajib lesson_id, listening wajib audio_url
        $v->sometimes('lesson_id', 'required', fn ($i) => $i['type'] === 'quiz');
        $v->sometimes('audio_url', 'required', fn ($i) => $i['type'] === 'listening');

        if ($v->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $v->errors(),
            ], 422);
        }
        $data = $v->validated();

        // Kepemilikan: quiz -> cek lesson milik teacher (via course.teacher_id)
        if ($data['type'] === 'quiz') {
            $lesson = Lesson::with('course')->find($data['lesson_id']);
            abort_unless($lesson, 404, 'Lesson tidak ditemukan.');
            abort_if(
                $lesson->course->teacher_id !== $request->user()->id,
                403,
                'Akses ditolak.'
            );
            if (! $lesson->video_url) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lesson ini tidak memiliki video. Tidak bisa generate soal.',
                ], 422);
            }
            $input = [
                'video_url' => $lesson->video_url,
                'count'     => $data['count'] ?? 5,
            ];
        } else {
            $input = [
                'audio_url' => $data['audio_url'],
                'count'     => $data['count'] ?? 5,
            ];
        }

        $gen = AiGeneration::create([
            'user_id' => $request->user()->id,
            'type'    => $data['type'],
            'input'   => $input,
            'status'  => 'queued',
        ]);

        $data['type'] === 'quiz'
            ? GenerateQuizQuestionsJob::dispatch($gen->id)
            : GenerateListeningQuestionsJob::dispatch($gen->id);

        return response()->json([
            'success' => true,
            'message' => 'Generate soal sedang diproses.',
            'data'    => ['uuid' => $gen->uuid],
        ], 202);
    }

    public function status(Request $request, string $uuid): JsonResponse
    {
        $gen = AiGeneration::where('uuid', $uuid)->first();
        abort_unless($gen, 404, 'Data generate tidak ditemukan.');

        // Hanya pemilik yang boleh polling statusnya
        abort_if($gen->user_id !== $request->user()->id, 403, 'Akses ditolak.');

        return response()->json([
            'success' => true,
            'message' => 'Status generate.',
            'data'    => [
                'status' => $gen->status,
                'result' => $gen->result,
                'error'  => $gen->error_message,
            ],
        ]);
    }
}