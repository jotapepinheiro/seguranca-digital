<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        $ttl = (int) Auth::factory()->getTTL();

        return response()->json(['success' => true, 'code' => 200, 'data' => [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in_minutes' => $ttl,
            'expires_in_date' => Carbon::now()->addMinutes($ttl)->format('d-m-Y H:i:s'),
        ]], 200);
    }
}
