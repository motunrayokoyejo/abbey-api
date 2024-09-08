<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{

    public function get(): JsonResponse
    {
        return response()->json([
            "message" => "Welcome to Abbey API",
        ], headers: ['cache-control' => 'no-cache', 'public,max-age=2500']);
    }

}
