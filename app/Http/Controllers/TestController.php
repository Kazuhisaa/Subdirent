<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    //
        /**
     * @OA\Get(
     *     path="/api/test",
     *     summary="Get test data",
     *     tags={"Test"},
     *     @OA\Response(
     *         response=200,
     *         description="OK"
     *     )
     * )
     */
    public function test()
    {
        return response()->json([
            'message' => 'Hello Swagger!'
        ]);
    }
}
