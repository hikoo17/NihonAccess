<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        // Ambil data yang dikirim oleh FE (menggunakan key 'username' agar sesuai dengan form Vue)
        $credentials = $request->validated();
        $loginInput = $credentials['username'] ?? $credentials['email'] ?? null;

        if (!$loginInput) {
            return $this->errorResponse('Input login tidak boleh kosong.', 422);
        }

        // Cari user berdasarkan email ATAU username agar fleksibel sesuai form di Frontend
        $user = User::query()
            ->where(function ($query) use ($loginInput) {
                $query->where('email', $loginInput)
                      ->orWhere('username', $loginInput);
            })
            ->with(['activeEnrollments.package'])
            ->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return $this->errorResponse('Kredensial login (Email/Username atau password) tidak valid.', 422);
        }

        if (! $user->is_active) {
            return $this->errorResponse('Akun tidak aktif.', 403);
        }

        // Jika dia adalah siswa (client), pastikan pendaftaran kelasnya masih aktif
        if ($user->role === 'client' && $user->activeEnrollments->isEmpty()) {
            return $this->errorResponse('Enrollment tidak aktif atau sudah expired.', 403);
        }

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