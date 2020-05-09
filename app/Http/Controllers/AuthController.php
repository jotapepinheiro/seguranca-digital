<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthLoginRequest;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\MediaType;

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
     * @Post(
     *     path="/auth/login",
     *     tags={"Login"},
     *     summary="Logar no sistema JWT por meio de e-mail e senha.",
     *     @Parameter(
     *         name="email",
     *         in="query",
     *         description="E-mail",
     *         required=true,
     *         example="super@super.com",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="password",
     *         in="query",
     *         description="Senha",
     *         required=true,
     *         example="super",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="remember",
     *         in="query",
     *         description="Lembre-me",
     *         required=false,
     *         example="true",
     *         @Schema(type="boolean")
     *     ),
     *     @Response(
     *         response="200",
     *         description= "Normal Response",
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 allOf={
     *                     @Schema(ref="#/components/schemas/ApiResponse"),
     *                     @Schema(
     *                         type="object",
     *                         @Property(property="data", ref="#/components/schemas/LoginProperty")
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
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
            return response()->json(['success' => false, 'code' => 401, 'message' => 'Usuário não autorizado.'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @Get(
     *     path="/auth/me",
     *     tags={"Auth"},
     *     summary="Retorna os dados do usuário logado.",
     *     security={{ "apiAuth": {} }},
     *     @Response(
     *         response="200",
     *         description="Resposta Operacional Normal",
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 allOf={
     *                     @Schema(ref="#/components/schemas/ApiResponse"),
     *                     @Schema(
     *                         type="object",
     *                         @Property(property="data", ref="#/components/schemas/MeResponse")
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *     @Response(response="401",description="Não autorizado"),
     *     @Response(response="403",description="Sem permissão de acesso")
     * )
     *
     * @return JsonResponse
     */
    public function me()
    {
        $user = Auth::user()->load('roles.permissions');

        return response()->json(['success' => true, 'code' => 200, 'data' => $user], 200);
    }

    /**
     * @Get(
     *     path="/auth/payoad",
     *     tags={"Auth"},
     *     summary="Retorna dados de payload JWT.",
     *     security={{ "apiAuth": {} }},
     *     @Response(
     *         response="200",
     *         description="Resposta Operacional Normal",
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 allOf={
     *                     @Schema(ref="#/components/schemas/ApiResponse"),
     *                     @Schema(
     *                         type="object",
     *                         @Property(property="data", ref="#/components/schemas/PayloadResponse")
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *     @Response(response="401",description="Não autorizado"),
     *     @Response(response="403",description="Sem permissão de acesso")
     * )
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

        return response()->json(['success' => true, 'code' => 200, 'data' => ['payload' => $payload, 'datas' => $dates]] , 200);
    }

    /**
     * @Post(
     *     path="/auth/logout",
     *     tags={"Auth"},
     *     summary="Desconecte o usuário (invalide o token).",
     *     security={{ "apiAuth": {} }},
     *     @Response(
     *         response="200",
     *         description="Resposta Operacional Normal",
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 allOf={
     *                     @Schema(ref="#/components/schemas/ApiResponse")
     *                 }
     *             )
     *         )
     *     ),
     *     @Response(response="401",description="Não autorizado"),
     *     @Response(response="403",description="Sem permissão de acesso")
     * )
     *
     * @return JsonResponse
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['success' => true, 'code' => 200, 'message' => 'Deslogado com Sucesso!!'], 200);
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
