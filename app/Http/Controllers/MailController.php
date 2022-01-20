<?php

namespace App\Http\Controllers;

use App\Mail\Example;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:100'],
            'phone' => ['required', 'numeric', 'max:11'],
            'subject' => ['required', 'max:100'],
            'content' => ['required'],
        ]);

        Mail::to($data['email'])
            ->send(new Example($data));

        return response()->json([
            'message' => '메일 발송',
        ]);
    }
}
