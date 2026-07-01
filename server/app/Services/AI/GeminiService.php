<?php

namespace App\Services\AI;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class GeminiService
{
    private string $apiKey;
    private string $model;
    private string $base;

    private const SUPPORTED_AUDIO = ['audio/wav', 'audio/mp3', 'audio/mpeg', 'audio/aac', 'audio/ogg', 'audio/flac', 'audio/aiff'];

    public function __construct()
    {
        $this->apiKey = (string) config('services.gemini.api_key', '');
        $this->model  = (string) config('services.gemini.model', 'gemini-2.5-flash');
        $this->base   = (string) config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta');

        if ($this->apiKey === '') {
            throw new RuntimeException('GEMINI_API_KEY belum dikonfigurasi.');
        }
    }

    /* ---------- API publik ---------- */

    public function generateQuizFromVideo(string $videoUrl, int $count = 5): array
    {
        $part   = $this->resolveVideoPart($videoUrl);
        $prompt = $this->quizPrompt($count);
        $items  = $this->callGenerateContent($part, $prompt, $this->quizSchema());

        return $this->validateQuiz($items);
    }

    public function generateListeningFromAudio(string $audioUrl, int $count = 5): array
    {
        $part   = $this->resolveAudioPart($audioUrl);
        $prompt = $this->listeningPrompt($count);
        $items  = $this->callGenerateContent($part, $prompt, $this->listeningSchema());

        return $this->validateListening($items);
    }

    /* ---------- Resolusi part media ---------- */

    /** @return array{text:string,file_data:array} */
    private function resolveVideoPart(string $url): array
    {
        $this->ensureValidUrl($url);

        // YouTube -> kirim langsung sebagai file_data.file_uri (tanpa upload)
        if (preg_match('#(youtube\.com/watch|youtu\.be/)#i', $url)) {
            return ['text' => '', 'file_data' => [
                'file_uri'  => $url,
                'mime_type' => 'video/mp4',
            ]];
        }

        // Self-hosted -> upload ke Files API, tunggu ACTIVE
        $file = $this->uploadAndWaitActive($url, 'video');
        return ['text' => '', 'file_data' => [
            'file_uri'  => $file['uri'],
            'mime_type' => $file['mime_type'],
        ]];
    }

    /** @return array{text:string,file_data:array} */
    private function resolveAudioPart(string $url): array
    {
        $this->ensureValidUrl($url);
        $file = $this->uploadAndWaitActive($url, 'audio');
        if (! in_array($file['mime_type'], self::SUPPORTED_AUDIO, true)) {
            throw new RuntimeException(
                'Format audio tidak didukung Gemini. Gunakan: ' . implode(', ', self::SUPPORTED_AUDIO)
            );
        }
        return ['text' => '', 'file_data' => [
            'file_uri'  => $file['uri'],
            'mime_type' => $file['mime_type'],
        ]];
    }

    /* ---------- Files API (resumable upload + polling) ---------- */

    /** @return array{uri:string,mime_type:string,name:string} */
    private function uploadAndWaitActive(string $url, string $kind): array
    {
        // 1. HEAD untuk ukuran + mime (fallback GET kalau HEAD tak didukung)
        $head = Http::withHeaders(['User-Agent' => 'NihonAccess/1.0'])->head($url);
        $size = (int) ($head->header('Content-Length') ?: 0);
        $mime = $head->header('Content-Type') ?: ($kind === 'video' ? 'video/mp4' : 'audio/mpeg');

        // Unduh ke file sementara (stream-friendly via sink untuk file besar)
        $tmp = tempnam(sys_get_temp_dir(), 'gem_');
        $dl  = Http::withOptions(['stream' => true])->sink($tmp)->get($url);
        if (! $dl->successful()) {
            @unlink($tmp);
            throw new RuntimeException('Tidak bisa mengunduh file dari URL: HTTP ' . $dl->status());
        }
        if ($size === 0) {
            $size = filesize($tmp) ?: 0;
        }
        $bytes = file_get_contents($tmp);
        @unlink($tmp);

        // 2. Mulai resumable upload -> dapat upload URL
        $start = Http::withHeaders([
            'X-Goog-Upload-Protocol'              => 'resumable',
            'X-Goog-Upload-Command'               => 'start',
            'X-Goog-Upload-Header-Content-Length' => (string) $size,
            'X-Goog-Upload-Header-Content-Type'   => $mime,
            'Content-Type'                         => 'application/json',
        ])->post("{$this->base}/upload/files?key={$this->apiKey}", [
            'file' => ['display_name' => 'upload_' . Str::random(8) . ($kind === 'video' ? '.mp4' : '.mp3')],
        ]);

        if (! $start->successful()) {
            throw $this->mapError($start->status(), $start->body(), $kind);
        }
        $uploadUrl = $start->header('X-Goog-Upload-URL');
        if (! $uploadUrl) {
            throw new RuntimeException('Gemini tidak memberikan upload URL.');
        }

        // 3. Upload + finalize
        $up = Http::withHeaders([
            'Content-Length'        => (string) $size,
            'X-Goog-Upload-Offset'  => '0',
            'X-Goog-Upload-Command' => 'upload, finalize',
        ])->withBody($bytes, $mime)->put($uploadUrl);

        if (! $up->successful()) {
            throw $this->mapError($up->status(), $up->body(), $kind);
        }
        $file = $up->json('file') ?? [];
        $name = $file['name'] ?? null;
        if (! $name) {
            throw new RuntimeException('Respon upload Gemini tidak valid.');
        }

        // 4. Polling sampai ACTIVE
        $max = (int) config('services.gemini.max_poll', 60);
        for ($i = 0; $i < $max; $i++) {
            $g     = Http::get("{$this->base}/{$name}?key={$this->apiKey}");
            $state = $g->json('state') ?? 'PROCESSING';
            if ($state === 'ACTIVE') {
                return [
                    'uri'       => $g->json('uri'),
                    'mime_type' => $g->json('mimeType') ?? $mime,
                    'name'      => $name,
                ];
            }
            if ($state === 'FAILED') {
                throw new RuntimeException('File gagal diproses Gemini.');
            }
            usleep(2_000_000); // 2 detik
        }
        throw new RuntimeException('Timeout menunggu file siap di Gemini.');
    }
        /* ---------- generateContent ---------- */

    private function callGenerateContent(array $part, string $prompt, array $schema): array
    {
        $body = [
            'contents' => [[
                'parts' => [
                    ['text' => $prompt],
                    ['file_data' => $part['file_data']],
                ],
            ]],
            'generationConfig' => [
                'responseMimeType' => 'application/json',
                'responseSchema'   => $schema,
                'temperature'      => 0.4,
            ],
        ];

        try {
            $res = Http::withHeaders(['Content-Type' => 'application/json'])
                ->timeout(180)
                ->post("{$this->base}/models/{$this->model}:generateContent?key={$this->apiKey}", $body);
        } catch (ConnectionException $e) {
            throw new RuntimeException('Koneksi ke Gemini gagal: ' . $e->getMessage());
        }

        if (! $res->successful()) {
            throw $this->mapError($res->status(), $res->body());
        }

        $text = $res->json('candidates.0.content.parts.0.text');
        if (! is_string($text) || $text === '') {
            $block = $res->json('promptFeedback.blockReason');
            throw new RuntimeException(
                $block ? "Permintaan diblokir Gemini ({$block})." : 'Gemini tidak mengembalikan konten.'
            );
        }

        $decoded = json_decode($text, true);
        if (! is_array($decoded)) {
            throw new RuntimeException('Output Gemini bukan JSON valid.');
        }
        return $decoded;
    }

    /* ---------- Schema ---------- */

    private function quizSchema(): array
    {
        return [
            'type' => 'ARRAY',
            'items' => [
                'type' => 'OBJECT',
                'properties' => [
                    'question'       => ['type' => 'STRING'],
                    'options'        => ['type' => 'ARRAY', 'items' => ['type' => 'STRING']],
                    'correct_answer' => ['type' => 'STRING'],
                    'explanation'    => ['type' => 'STRING'],
                ],
                'required' => ['question', 'options', 'correct_answer', 'explanation'],
                'propertyOrdering' => ['question', 'options', 'correct_answer', 'explanation'],
            ],
        ];
    }

    private function listeningSchema(): array
    {
        return [
            'type' => 'ARRAY',
            'items' => [
                'type' => 'OBJECT',
                'properties' => [
                    'question'       => ['type' => 'STRING'],
                    'options'        => ['type' => 'ARRAY', 'items' => ['type' => 'STRING'], 'nullable' => true],
                    'correct_answer' => ['type' => 'STRING'],
                ],
                'required' => ['question', 'options', 'correct_answer'],
                'propertyOrdering' => ['question', 'options', 'correct_answer'],
            ],
        ];
    }

    /* ---------- Validasi output model ---------- */

    private function validateQuiz(array $items): array
    {
        $clean = [];
        foreach ($items as $q) {
            if (! is_array($q)) {
                continue;
            }
            foreach (['question', 'correct_answer', 'explanation'] as $k) {
                if (! is_string($q[$k] ?? null) || trim($q[$k]) === '') {
                    continue 2;
                }
            }
            $opts = $q['options'] ?? [];
            if (! is_array($opts) || count($opts) < 2) {
                continue ;
            }
            $opts = array_values(array_map('strval', $opts));
            if (! in_array($q['correct_answer'], $opts, true)) {
                continue ;
            }
            $clean[] = [
                'question'       => trim($q['question']),
                'options'         => $opts,
                'correct_answer'  => $q['correct_answer'],
                'explanation'     => trim($q['explanation']),
            ];
        }
        if (empty($clean)) {
            throw new RuntimeException('Gemini tidak menghasilkan soal yang valid sesuai skema.');
        }
        return $clean;
    }

    private function validateListening(array $items): array
    {
        $clean = [];
        foreach ($items as $q) {
            if (! is_array($q)) {
                continue;
            }
            if (! is_string($q['question'] ?? null) || trim($q['question']) === '') {
                continue;
            }
            if (! is_string($q['correct_answer'] ?? null) || trim($q['correct_answer']) === '') {
                continue;
            }
            $opts = $q['options'] ?? null;
            if ($opts !== null) {
                if (! is_array($opts)) {
                    continue;
                }
                $opts = array_values(array_map('strval', $opts));
            }
            $clean[] = [
                'question'      => trim($q['question']),
                'options'        => $opts,
                'correct_answer' => trim($q['correct_answer']),
            ];
        }
        if (empty($clean)) {
            throw new RuntimeException('Gemini tidak menghasilkan soal listening yang valid.');
        }
        return $clean;
    }

    /* ---------- Prompt ---------- */

    private function quizPrompt(int $count): string
    {
        return "Kamu asisten pembuat soal bahasa Jepang untuk pemula (JLPT N5). "
            . "Tonton video lesson dan buat persis {$count} soal pilihan ganda. "
            . "Bahasa soal & penjelasan: Indonesia. Opsi jawaban boleh berisi kata/frasa Jepang. "
            . "Pastikan jawaban benar dan opsi benar-benar sesuai isi video. "
            . "Setiap soal WAJIB punya tepat 4 opsi, dan correct_answer harus sama persis dengan salah satu opsi.";
    }

    private function listeningPrompt(int $count): string
    {
        return "Kamu asisten pembuat soal listening bahasa Jepang (JLPT N5). "
            . "Dengarkan audio dan buat {$count} soal. "
            . "Jika soal pilihan ganda, isi 'options' dengan 4 opsi string; "
            . "jika soal isian, set 'options' ke null. "
            . "Bahasa soal: Indonesia. 'correct_answer' harus jawaban yang benar-benar ada di audio.";
    }

    /* ---------- Helper ---------- */

    private function ensureValidUrl(string $url): void
    {
        if (! filter_var($url, FILTER_VALIDATE_URL) || ! preg_match('#^https?://#i', $url)) {
            throw new RuntimeException('URL tidak valid. Pastikan diawali http:// atau https://');
        }
    }

    private function mapError(int $status, string $body, string $kind = 'media'): RuntimeException
    {
        $low = strtolower($body);
        return match (true) {
            str_contains($low, 'api key not valid') => new RuntimeException('API key Gemini tidak valid. Hubungi admin.'),
            str_contains($low, 'quota') || $status === 429 => new RuntimeException('Kuota Gemini habis. Coba lagi nanti.'),
            str_contains($low, 'cannot get video') || str_contains($low, 'private') || str_contains($low, 'unlisted')
                => new RuntimeException('Video/audio tidak bisa diakses Gemini (mungkin private/unlisted). Pastikan publik.'),
            str_contains($low, 'too large') || str_contains($low, 'maximum')
                => new RuntimeException($kind === 'video'
                    ? 'Video terlalu panjang (maks ~2 jam).'
                    : 'Audio terlalu panjang (maks ~9.5 jam).'),
            $status >= 500 => new RuntimeException('Server Gemini bermasalah. Coba lagi sebentar lagi.'),
            default => new RuntimeException('Gemini gagal memproses (HTTP ' . $status . ').'),
        };
    }
}