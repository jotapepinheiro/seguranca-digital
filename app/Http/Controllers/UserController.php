<?php

namespace App\Http\Controllers;

use App\User;
use http\Exception;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Put;
use OpenApi\Annotations\RequestBody;
use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Property;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Parameter;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserRegisterRequest;

class UserController extends Controller
{
    protected $user;

    /**
     * UserController constructor.
     * @param User $users
     */
    public function __construct(User $users)
    {
        $this->middleware('auth:api', ['except' => ['register']]);
        $this->user = $users;
    }

    /**
     * @Get(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Lista de usuários.",
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
     *                         @Property(property="data", ref="#/components/schemas/UsersPaginateResponse")
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     */
    public function index()
    {
        $user = $this->user
            ->with('roles')
            ->orderBy('id','DESC')
            ->paginate(100);

        return response()->json(['success' => true, 'code' => 200, 'data' => $user], 200);
    }

    /**
     * @Post(
     *     path="/auth/register",
     *     tags={"Login"},
     *     summary="Registrar uma nova conta no sistema.",
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"name", "email", "password", "password_confirmation"},
     *                 @Property(property="name", type="string", description="Nome"),
     *                 @Property(property="email", type="string", description="E-mail"),
     *                 @Property(property="password", type="string", description="Senha"),
     *                 @Property(property="password_confirmation", type="string", description="Confirmar Senha")
     *             )
     *         )
     *     ),
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
     * @param UserRegisterRequest $request
     * @return JsonResponse
     */
    public function register(UserRegisterRequest $request)
    {
        try {
            $input = $request->only('name', 'email', 'password');
            $input['password'] = app('hash')->make($input['password']);

            if( $user = $this->user->create($input) ) {

                $user->attachRole(3);

                $code = 200;
                $output = [
                    'success' => true,
                    'code' => $code,
                    'user' => $user,
                    'message' => 'Usuário cadastrado com sucesso!!'
                ];
            } else {
                $code = 500;
                $output = [
                    'success' => false,
                    'code' => $code,
                    'message' => 'Erro ao cadastrar o usuário!!'
                ];
            }

        } catch (Exception $e) {
            //dd($e->getMessage());
            $code = 500;
            $output = [
                'success' => false,
                'code' => $code,
                'message' => 'Erro ao cadastrar o usuário!!'
            ];
        }

        return response()->json($output, $code);

    }

    /**
     * @Get(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Listar usuário por ID.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Usuário",
     *         required=true,
     *         @Schema(type="integer")
     *     ),
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
     *                         @Property(property="data", ref="#/components/schemas/UserProperty")
     *                     )
     *                 }
     *             )
     *         )
     *     )
     * )
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $user = $this->user
            ->with('roles.permissions')
            ->findOrFail($id);

        return response()->json(['success' => true, 'code' => 200, 'data' => $user], 200);
    }

    /**
     * @Post(
     *     path="/users",
     *     tags={"Users"},
     *     summary="Cadastrar usuário.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="roles[]",
     *         in="query",
     *         description="Perfis do Usuário",
     *         required=true,
     *         @Schema(type="array", @Items(type="integer"))
     *     ),
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"name", "email", "password", "password_confirmation"},
     *                 @Property(property="name", type="string", description="Nome"),
     *                 @Property(property="email", type="string", description="E-mail"),
     *                 @Property(property="password", type="string", description="Senha"),
     *                 @Property(property="password_confirmation", type="string", description="Confirmar Senha")
     *             )
     *         )
     *     ),
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
     * @param UserStoreRequest $request
     * @return JsonResponse
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $input = $request->only('name', 'email', 'password', 'roles');
            $input['password'] = app('hash')->make($input['password']);

            if( $user = $this->user->create($input) ) {

                if ($request->has('roles')) {
                    foreach ($request->input('roles') as $key => $value) {
                        $user->attachRole($value);
                    }
                }

                $code = 200;
                $output = [
                    'success' => true,
                    'code' => $code,
                    'user' => $user,
                    'message' => 'Usuário cadastrado com sucesso!!'
                ];
            } else {
                $code = 500;
                $output = [
                    'success' => false,
                    'code' => $code,
                    'message' => 'Erro ao cadastrar o usuário!!'
                ];
            }

        } catch (Exception $e) {
            //dd($e->getMessage());
            $code = 500;
            $output = [
                'success' => false,
                'code' => $code,
                'message' => 'Erro ao cadastrar o usuário!!'
            ];
        }

        return response()->json($output, $code);
    }

    /**
     * @Put(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Atualizar usuário.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Usuário",
     *         required=true,
     *         @Schema(type="integer")
     *     ),
     *     @Parameter(
     *         name="roles[]",
     *         in="query",
     *         description="Perfis do Usuário",
     *         required=true,
     *         @Schema(type="array", @Items(type="integer"))
     *     ),
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"name", "email"},
     *                 @Property(property="name", type="string", description="Nome do Usuário"),
     *                 @Property(property="email", type="string", description="E-mail do Usuário")
     *             )
     *         )
     *     ),
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
     * @param int $id
     * @param UserUpdateRequest $request
     * @return JsonResponse
     */
    public function update($id, UserUpdateRequest $request)
    {
        $user = $this->user->findOrFail($id);

        $input = $request->only('name', 'email', 'roles');

        $user->update($input);

        if ($request->has('roles')) {
            foreach ($request->input('roles') as $key => $value) {
                $user->attachRole($value);
            }
        }

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }

    /**
     * @Delete(
     *     path="/users/{id}",
     *     tags={"Users"},
     *     summary="Deletar usuário.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Usuário",
     *         required=true,
     *         @Schema(type="integer")
     *     ),
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
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $user = $this->user->findOrFail($id);

        if($user->hasRole('super')) {
            return response()->json(['success' => true, 'code' => 401, 'data' => ['message' => 'Usuário não pode ser excluído.']], 401);
        }

        $user->delete();

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }
}
