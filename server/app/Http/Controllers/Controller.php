<?php

namespace App\Http\Controllers;
use Illuminate\Http\JsonResponse;
abstract class Controller
{
    protected function paginatedResponse(string $message, $paginator, string $resourceClass): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $resourceClass::collection($paginator),
            'meta'    => [
                'current_page' => $paginator->currentPage(),
                'last_page'    => $paginator->lastPage(),
                'per_page'     => $paginator->perPage(),
                'total'        => $paginator->total(),
            ],
        ]);
    }
}