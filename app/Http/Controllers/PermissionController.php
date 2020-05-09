<?php

namespace App\Http\Controllers;

use App\Permission;
use OpenApi\Annotations\Put;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Schema;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\RequestBody;
use App\Http\Requests\PermissionStoreRequest;
use App\Http\Requests\PermissionUpdateRequest;

class PermissionController extends Controller
{
    private $permission;

    /**
     * Create a new controller instance.
     *
     * @param Permission $permission
     */
    public function __construct(Permission $permission)
    {
        $this->middleware("auth:api");
        $this->permission = $permission;
    }

    /**
     * @Get(
     *     path="/permissions",
     *     tags={"Permissions"},
     *     summary="Lista de permissões.",
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
     *                         @Property(property="data", ref="#/components/schemas/PermissionsPaginateResponse")
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
    public function index()
    {
        $permission = $this->permission->orderBy('id','DESC')->paginate(50);

        return response()->json(['success' => true, 'code' => 200, 'data' => $permission], 200);
    }

    /**
     * @Post(
     *     path="/permissions",
     *     tags={"Permissions"},
     *     summary="Criar nova permissão.",
     *     security={{ "apiAuth": {} }},
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"name", "display_name", "description"},
     *                 @Property(property="name", type="string", description="Nome da Permissão"),
     *                 @Property(property="display_name", type="string", description="Nome de Exibição"),
     *                 @Property(property="description", type="string", description="Descrição")
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
     * @param PermissionStoreRequest $request
     * @return JsonResponse
     */
    public function store(PermissionStoreRequest $request)
    {
        $this->permission->create($request->all());

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }

    /**
     * @Get(
     *     path="/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Listar permissão por ID.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id da Permissão",
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
     *                         @Property(property="data", ref="#/components/schemas/PermissionProperty")
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *     @Response(response="401",description="Não autorizado"),
     *     @Response(response="403",description="Sem permissão de acesso")
     * )
     *
     * @param $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $permission = $this->permission->findOrFail($id);

        return response()->json($permission);
    }

    /**
     * @Put(
     *     path="/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Atualizar permissão.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id da Permissão",
     *         required=true,
     *         @Schema(type="integer")
     *     ),
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"display_name", "description"},
     *                 @Property(property="display_name", type="string", description="Nome de Exibição"),
     *                 @Property(property="description", type="string", description="Descrição")
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
     * @param PermissionStoreRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update($id, PermissionUpdateRequest $request)
    {
        $permission = $this->permission->findOrFail($id);

        $permission->update($request->all());

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }

    /**
     * @Delete(
     *     path="/permissions/{id}",
     *     tags={"Permissions"},
     *     summary="Deletar permissão.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id da Permissão",
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
        $permission = $this->permission->findOrFail($id);

        $permission->delete();

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);

    }
}
