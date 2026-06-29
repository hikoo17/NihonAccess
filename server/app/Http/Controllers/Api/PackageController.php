<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\JsonResponse;

class PackageController extends Controller
{
    public function index(): JsonResponse
    {
        $packages = Package::active()
            ->orderByRaw("FIELD(type, 'online', 'onsite')")
            ->orderBy('price')
            ->with('features')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $packages,
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $package = Package::active()
            ->where('slug', $slug)
            ->with('features', 'courses')
            ->first();

        if (! $package) {
            return response()->json([
                'status' => 'error',
                'message' => 'Paket tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $package,
        ]);
    }
}
