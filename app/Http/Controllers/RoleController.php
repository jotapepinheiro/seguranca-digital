<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Put;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Items;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Schema;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\RequestBody;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;


class RoleController extends Controller
{

    private Role $role;
    private Permission $permission;

    /**
     * Create a new controller instance.
     *
     * @param Role $role
     * @param Permission $permission
     */
    public function __construct(Role $role, Permission $permission)
    {
        $this->middleware("auth:api");
        $this->role = $role;
        $this->permission = $permission;
    }

    /**
     * @Get(
     *     path="/roles",
     *     tags={"Roles"},
     *     summary="Lista de perfis.",
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
     *                         @Property(property="data", ref="#/components/schemas/RolesPaginateResponse")
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
    public function index(): JsonResponse
    {
        $role = $this->role->orderBy('id','DESC')->paginate(5);

        return response()->json(['success' => true, 'code' => 200, 'data' => $role]);
    }

    /**
     * Mostre o formulário para criar um novo perfil.
     *
     * @return mixed
     */
    public function create()
    {
        return $this->permission->pluck('display_name','id');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function edit(int $id): JsonResponse
    {
        $role = $this->role->findOrFail($id);
        $permissions = $this->permission->get();

        DB::table("permission_role")
            ->where("role_id",$id)
            ->pluck('permission_id')
            ->toArray();

        return response()->json(['success' => true, 'code' => 200, 'data' => [$role, $permissions]]);
    }

    /**
     * @Post(
     *     path="/roles",
     *     tags={"Roles"},
     *     summary="Criar novo perfil.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="permissions[]",
     *         in="query",
     *         description="Permissões do Perfil",
     *         required=true,
     *         @Schema(type="array", @Items(type="integer"))
     *     ),
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"name", "display_name", "description"},
     *                 @Property(property="name", type="string", description="Nome do Perfil"),
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
     * @param RoleStoreRequest $request
     * @return JsonResponse
     */
    public function store(RoleStoreRequest $request): JsonResponse
    {
        $role = new Role();
        $role->name = $request->input('name');
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();

        foreach ($request->input('permissions') as $value) {
            $role->attachPermission($value);
        }

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']]);
    }

    /**
     * @Get(
     *     path="/roles/{id}",
     *     tags={"Roles"},
     *     summary="Listar perfil por ID.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Perfil",
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
     *                         @Property(property="data", ref="#/components/schemas/RoleProperty")
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
    public function show($id): JsonResponse
    {
        $role = $this->role->with('permissions')->findOrFail($id);

        return response()->json(['success' => true, 'code' => 200, 'data' => $role]);
    }

    /**
     * @Put(
     *     path="/roles/{id}",
     *     tags={"Roles"},
     *     summary="Atualizar perfil.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Perfil",
     *         required=true,
     *         @Schema(type="integer")
     *     ),
     *     @Parameter(
     *         name="permissions[]",
     *         in="query",
     *         description="Permissões do Perfil",
     *         required=true,
     *         @Schema(type="array", @Items(type="integer"))
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
     * @param RoleUpdateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(int $id, RoleUpdateRequest $request): JsonResponse
    {
        //Find the role and update its details
        $role = $this->role->findOrFail($id);
        $role->display_name = $request->input('display_name');
        $role->description = $request->input('description');
        $role->save();

        //delete all permissions currently linked to this role
        DB::table("permission_role")->where("role_id",$id)->delete();

        //attach the new permissions to the role
        foreach ($request->input('permissions') as $value) {
            $role->attachPermission($value);
        }

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']]);

    }

    /**
     * @Delete(
     *     path="/roles/{id}",
     *     tags={"Roles"},
     *     summary="Deletar perfil e permissões em cascata.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Perfil",
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
    public function destroy(int $id): JsonResponse
    {
        $role = $this->role->findOrFail($id);

        $role->delete();

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']]);

    }

}
