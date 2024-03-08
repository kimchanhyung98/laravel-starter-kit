<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class HealthCheckController extends Controller
{
    /**
     * AWS HealthCheck
     */
    #[OA\Get(path: '/healthcheck', description: '서버 상태 확인', responses: [
        new OA\Response(response: 200, description: '200.success', content: [
            new OA\JsonContent(properties: [
                new OA\Property(property: 'status', type: 'string', example: 'healthy'),
            ]),
        ]),
        new OA\Response(response: 500, description: 'error.500.server', content: [
            new OA\JsonContent(properties: [
                new OA\Property(property: 'status', type: 'string', example: 'unhealthy'),
            ]),
        ]),
    ])]
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => ! Post::first() ? 'unhealthy' : 'healthy',
        ]);
    }
}
