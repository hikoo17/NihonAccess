<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        // 1. Validasi manual langsung di sini
        $request->validate([
            'username' => 'required_without:email|string',
            'email'    => 'required_without:username|string',
            'password' => 'required|string',
        ]);

        // 2. Ambil input login (bisa 'username' atau 'email' tergantung apa yang dikirim FE)
        $loginInput = $request->input('username') ?? $request->input('email');

        // 3. Cari user berdasarkan email ATAU username
        $user = User::query()
            ->where(function ($query) use ($loginInput) {
                $query->where('email', $loginInput)
                      ->orWhere('username', $loginInput);
            })
            ->with(['activeEnrollments.package'])
            ->first();

        // 4. Cek apakah user ada dan password-nya cocok
        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return $this->errorResponse('Kredensial login (Email/Username atau password) tidak valid.', 422);
        }

        // 5. Cek status keaktifan user
        if (! $user->is_active) {
            return $this->errorResponse('Akun tidak aktif.', 403);
        }

        // 6. Jika dia adalah siswa (client), pastikan pendaftaran kelasnya masih aktif
        if ($user->role === 'client' && $user->activeEnrollments->isEmpty()) {
            return $this->errorResponse('Enrollment tidak aktif atau sudah expired.', 403);
        }

        // 7. Buat token Sanctum
        $token = $user->createToken('api-token')->plainTextToken;

        return $this->successResponse('Login berhasil.', [
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => new UserResource($user),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();

        return $this->successResponse('Logout berhasil.');
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['activeEnrollments.package']);

        if (! $user->is_active) {
            return $this->errorResponse('Akun tidak aktif.', 403);
        }

        if ($user->role === 'client' && $user->activeEnrollments->isEmpty()) {
            return $this->errorResponse('Enrollment tidak aktif atau sudah expired.', 403);
        }

        return $this->successResponse('Data user berhasil diambil.', [
            'user' => new UserResource($user),
        ]);
    }

    private function successResponse(string $message, mixed $data = null, array $meta = []): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta !== []) {
            $response['meta'] = $meta;
        }

        return response()->json($response);
    }

    private function errorResponse(string $message, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'data' => null,
        ], $status);
    }
}