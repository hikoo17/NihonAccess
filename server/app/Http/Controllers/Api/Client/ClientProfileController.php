<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientProfileController extends Controller
{
    /**
     * Tampilkan profil user yang sedang login.
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'success' => true,
            'data' => $this->profileData($user),
        ]);
    }

    /**
     * Format data profil yang konsisten.
     */
    private function profileData($user): array
    {
        return [
            'id'                => $user->id,
            'name'              => $user->name,
            'email'             => $user->email,
            'whatsapp'          => $user->whatsapp,
            'username'          => $user->username,
            'role'              => $user->role,
            'is_active'         => $user->is_active,
            'email_verified_at' => $user->email_verified_at?->toDateTimeString(),
            'created_at'        => $user->created_at?->toDateTimeString(),
        ];
    }
}
