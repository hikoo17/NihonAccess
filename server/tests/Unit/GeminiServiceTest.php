<?php

namespace Tests\Unit;

use App\Services\AI\GeminiService;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use PHPUnit\Framework\Attributes\Test;
use RuntimeException;
use Tests\TestCase;

class GeminiServiceTest extends TestCase
{
    private GeminiService $service;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('services.gemini.api_key', 'test-key');
        $this->service = new GeminiService();
    }

    #[Test]
    public function quiz_dari_video_youtube_mengembalikan_soal_valid(): void
    {
        Http::fake([
            '*generateContent*' => Http::response([
                'candidates' => [[
                    'content' => ['parts' => [[
                        'text' => json_encode([
                            [
                                'question'       => 'Apa arti "konnichiwa"?',
                                'options'         => ['Selamat pagi', 'Selamat siang', 'Selamat malam', 'Sampai jumpa'],
                                'correct_answer'  => 'Selamat siang',
                                'explanation'     => 'Sapaan siang hari',
                            ],
                        ], JSON_THROW_ON_ERROR),
                    ]]],
                ]],
            ], 200),
        ]);

        $result = $this->service->generateQuizFromVideo('https://www.youtube.com/watch?v=dQw4w9WgXcQ', 1);

        $this->assertCount(1, $result);
        $this->assertSame('Selamat siang', $result[0]['correct_answer']);
        $this->assertCount(4, $result[0]['options']);

        Http::assertSentCount(1);
    }

    #[Test]
    public function listening_dari_audio_self_hosted_upload_lalu_generate(): void
    {
        $audioUrl = 'https://example.com/audio.mp3';
        $uploadSessionUrl = 'https://upload.example.com/session/abc';

        Http::fake(function (Request $request) use ($audioUrl, $uploadSessionUrl) {
            $url = $request->url();
            $method = $request->method();

            if ($method === 'HEAD' && str_contains($url, 'example.com/audio.mp3')) {
                return Http::response('', 200, ['Content-Length' => '1024', 'Content-Type' => 'audio/mpeg']);
            }

            if ($method === 'GET' && str_contains($url, 'example.com/audio.mp3')) {
                return Http::response('audio-bytes', 200, ['Content-Type' => 'audio/mpeg']);
            }

            if ($method === 'POST' && str_contains($url, 'upload/files')) {
                return Http::response(['file' => ['name' => 'files/abc']], 200, [
                    'X-Goog-Upload-URL' => $uploadSessionUrl,
                ]);
            }

            if ($method === 'PUT' && str_contains($url, 'upload.example.com/session')) {
                return Http::response(['file' => ['name' => 'files/abc']]);
            }

            if ($method === 'GET' && str_contains($url, 'files/abc')) {
                return Http::response([
                    'state'     => 'ACTIVE',
                    'uri'       => 'https://generativelanguage.googleapis.com/v1beta/files/abc-uri',
                    'mimeType'  => 'audio/mpeg',
                ]);
            }

            if ($method === 'POST' && str_contains($url, 'generateContent')) {
                return Http::response([
                    'candidates' => [[
                        'content' => ['parts' => [[
                            'text' => json_encode([
                                [
                                    'question'       => 'Apa yang didengar di awal audio?',
                                    'options'         => null,
                                    'correct_answer'  => 'konnichiwa',
                                ],
                            ], JSON_THROW_ON_ERROR),
                        ]]],
                    ]],
                ], 200);
            }

            return Http::response('not faked', 404);
        });

        $result = $this->service->generateListeningFromAudio($audioUrl, 1);

        $this->assertCount(1, $result);
        $this->assertNull($result[0]['options']);
        $this->assertSame('konnichiwa', $result[0]['correct_answer']);
    }

    #[Test]
    public function output_bukan_json_valid_melempar_exception(): void
    {
        Http::fake([
            '*generateContent*' => Http::response([
                'candidates' => [[
                    'content' => ['parts' => [['text' => 'ini bukan JSON']]],
                ]],
            ], 200),
        ]);

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Output Gemini bukan JSON valid.');

        $this->service->generateQuizFromVideo('https://www.youtube.com/watch?v=x', 3);
    }

    #[Test]
    public function api_key_kosong_melempar_exception(): void
    {
        Config::set('services.gemini.api_key', '');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('GEMINI_API_KEY belum dikonfigurasi.');

        new GeminiService();
    }
}
