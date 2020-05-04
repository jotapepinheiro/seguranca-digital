<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthLoginRequest;

class AuthController extends Controller
{

    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @param AuthLoginRequest $request
     * @return JsonResponse
     */
    public function login(AuthLoginRequest $request)
    {
        $input = $request->only('email', 'password');

        if($request->input('remember')) {
            $token_ttl = env('JWT_TTL_REMEMBER_ME', 1);
            Auth::factory()->setTTL($token_ttl * 10080);
        }

        if (! $token = Auth::attempt($input)) {
            return response()->json(['error' => 'Usuário não autorizado.'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return JsonResponse
     */
    public function me()
    {
        $user = Auth::user()->load('roles.permissions');

        return response()->json($user, 200);
    }

    /**
     * Get the payload JWT.
     *
     * @return JsonResponse
     */
    public function payoad()
    {
        $payload = Auth::payload();

        $dates = [
            'emitido_em' => Carbon::createFromTimestamp($payload('iat'))->format('d-m-Y H:i:s'),
            'expira_em' => Carbon::createFromTimestamp($payload('exp'))->format('d-m-Y H:i:s'),
            'nao_antes_de' => Carbon::createFromTimestamp($payload('nbf'))->format('d-m-Y H:i:s')
        ];

        return response()->json(['payload' => $payload, 'datas' => $dates], 200);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Deslogado com Sucesso!!'], 200);
    }

    /**
     * Refresh a token.
     *
     * @return JsonResponse
     */
    public function refresh()
    {
        try {
            $newToken = Auth::refresh();
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

        return $this->respondWithToken($newToken);
    }

}
