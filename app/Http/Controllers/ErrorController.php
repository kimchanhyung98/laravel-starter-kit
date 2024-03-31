<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function __invoke(Request $request)
    {
        abort($request->code ?? 500, $request->message ?? null);
    }
}
