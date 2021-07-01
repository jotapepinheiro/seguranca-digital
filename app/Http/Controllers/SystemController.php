<?php

namespace App\Http\Controllers;

use App\Models\System;
use App\Models\Controle;
use OpenApi\Annotations\Put;
use OpenApi\Annotations\Get;
use OpenApi\Annotations\Post;
use OpenApi\Annotations\Delete;
use OpenApi\Annotations\Schema;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations\Property;
use OpenApi\Annotations\Response;
use OpenApi\Annotations\MediaType;
use OpenApi\Annotations\Parameter;
use OpenApi\Annotations\RequestBody;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\SystemIndexRequest;
use App\Http\Requests\SystemStoreRequest;
use App\Http\Requests\SystemUpdateRequest;

class SystemController extends Controller
{
    private $system;
    private $controle;

    /**
     * Create a new controller instance.
     *
     * @param System $system
     * @param Controle $controle
     */
    public function __construct(System $system, Controle $controle)
    {
        $this->middleware("auth:api");
        $this->system = $system;
        $this->controle = $controle;
    }

    /**
     * @Get(
     *     path="/systems",
     *     tags={"Systems"},
     *     summary="Lista de sistemas.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="include",
     *         in="query",
     *         description="Incluir controle,controle.user ou controles,controles.user ou createdBy,updatedBy",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[description]",
     *         in="query",
     *         description="Filtrar por descrição do sistema",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[initial]",
     *         in="query",
     *         description="Filtrar por sigla do sistema",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[email]",
     *         in="query",
     *         description="Filtrar por e-mail do técnico",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[status]",
     *         in="query",
     *         description="Filtrar por status do sistema",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[controles.user.name]",
     *         in="query",
     *         description="Filtrar por nome do técnico",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[controles.user.email]",
     *         in="query",
     *         description="Filtrar por e-mail do técnico",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[controle.user.name]",
     *         in="query",
     *         description="Filtrar por nome de usuário da justificativa",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[createdBy.name]",
     *         in="query",
     *         description="Filtrar por nome de usuário que criou o sistema",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[updatedBy.name]",
     *         in="query",
     *         description="Filtrar por nome de usuário que alterou o sistema",
     *         @Schema(type="string")
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
     *                         @Property(property="data", ref="#/components/schemas/SystemsPaginateResponse")
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *     @Response(response="401",description="Não autorizado"),
     *     @Response(response="403",description="Sem permissão de acesso")
     * )
     *
     * @param SystemIndexRequest $request
     * @return JsonResponse
     */
    public function index(SystemIndexRequest $request): JsonResponse
    {
        $systems = QueryBuilder::for(System::class, $request)
            ->allowedFilters([
                'description', 'initial', 'email', 'status',
                'controles.user.name', 'controle.user.name',
                'controles.user.email', 'controle.user.email',
                'createdBy.name', 'updatedBy.name'
            ])
            ->allowedIncludes([
                'controle', 'controle.user',
                'controles', 'controles.user',
                'createdBy.name', 'updatedBy.name'
            ])
            ->defaultSort('-id')
            ->allowedSorts('description', 'initial', 'email', 'url', 'status')
            ->paginate(50)
            ->appends(request()->query());

        return response()->json(['success' => true, 'code' => 200, 'data' => $systems], 200);
    }

    /**
     * @Get(
     *     path="/systems/historic/{id}",
     *     tags={"Systems"},
     *     summary="Listar histórico de justificativas de alterações de um sistema por ID.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Sistema",
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
     *                         @Property(property="data", ref="#/components/schemas/SystemProperty")
     *                     )
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
    public function historico(int $id): JsonResponse
    {
        $system = $this->system
            ->with('createdBy')
            ->with('updatedBy')
            ->with('controles')
            ->with('controles.user')
            ->findOrFail($id);

        return response()->json(['success' => true, 'code' => 200, 'data' => $system], 200);
    }

    /**
     * @Get(
     *     path="/systems/{id}",
     *     tags={"Systems"},
     *     summary="Listar sistema com o último controles por ID.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Sistema",
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
     *                         @Property(property="data", ref="#/components/schemas/SystemProperty")
     *                     )
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
    public function show(int $id): JsonResponse
    {
        $system = $this->system
            ->with('createdBy')
            ->with('updatedBy')
            ->with('controle')
            ->with('controle.user')
            ->findOrFail($id);

        return response()->json(['success' => true, 'code' => 200, 'data' => $system], 200);
    }

    /**
     * @Post(
     *     path="/systems",
     *     tags={"Systems"},
     *     summary="Criar novo sistema.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="status",
     *         in="query",
     *         description="Status do Sistema",
     *         required=true,
     *         @Schema(type="string", enum={"active", "canceled"})
     *     ),
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"description", "initial"},
     *                 @Property(property="description", type="string", description="Descrição"),
     *                 @Property(property="initial", type="string", description="Sigla"),
     *                 @Property(property="email", type="string", description="E-mail"),
     *                 @Property(property="url", type="string", description="Url")
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
     * @param SystemStoreRequest $request
     * @return JsonResponse
     */
    public function store(SystemStoreRequest $request): JsonResponse
    {
        $this->system->create($request->all());

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }

    /**
     * @Put(
     *     path="/systems/{id}",
     *     tags={"Systems"},
     *     summary="Atualizar sistema e criar novo controle caso exista o parametro justificativa.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Sistema",
     *         required=true,
     *         @Schema(type="integer")
     *     ),
     *     @Parameter(
     *         name="status",
     *         in="query",
     *         description="Status do Sistema",
     *         required=true,
     *         @Schema(type="string", enum={"active", "canceled"})
     *     ),
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"description", "initial"},
     *                 @Property(property="description", type="string", description="Descrição"),
     *                 @Property(property="initial", type="string", description="Sigla"),
     *                 @Property(property="email", type="string", description="E-mail"),
     *                 @Property(property="url", type="string", description="Url"),
     *                 @Property(property="justification", type="string", description="Justificativa")
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
     * @param SystemUpdateRequest $request
     * @return JsonResponse
     */
    public function update(int $id, SystemUpdateRequest $request): JsonResponse
    {
        $system = $this->system->findOrFail($id);

        $system->update($request->all());

        if ($request->has('justification')) {
            $this->controle->create([
                'system_id' => $system->id,
                'user_id' => Auth::id(),
                'justification' => $request->input('justification')
            ]);
        }

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }

    /**
     * @Delete(
     *     path="/systems/{id}",
     *     tags={"Systems"},
     *     summary="Deletar sistema e controle em cascata.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="id",
     *         in="path",
     *         description="Id do Sistema",
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
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $system = $this->system->findOrFail($id);

        $system->delete();

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }
}
