<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;

class HealthCheckController extends Controller
{
    /**
     * HealthCheck.
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => !Post::first() ? 'unhealthy' : 'healthy',
        ]);
    }
}
