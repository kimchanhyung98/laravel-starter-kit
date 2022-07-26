<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class HealthCheckController extends Controller
{
    /**
     * AWS EB HealthCheck
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'healthy',
        ]);
    }
}
