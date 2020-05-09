<?php

namespace App\Http\Controllers;

use App\Sistema;
use App\Controle;
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
use App\Http\Requests\SistemaIndexRequest;
use App\Http\Requests\SistemaStoreRequest;
use App\Http\Requests\SistemaUpdateRequest;

class SistemaController extends Controller
{
    private $sistema;
    private $controle;

    /**
     * Create a new controller instance.
     *
     * @param Sistema $sistema
     * @param Controle $controle
     */
    public function __construct(Sistema $sistema, Controle $controle)
    {
        $this->middleware("auth:api");
        $this->sistema = $sistema;
        $this->controle = $controle;
    }

    /**
     * @Get(
     *     path="/sistemas",
     *     tags={"Sistemas"},
     *     summary="Lista de sistemas.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="include",
     *         in="query",
     *         description="Incluir controle,controle.user ou controles,controles.user ou createdBy,updatedBy",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[descricao]",
     *         in="query",
     *         description="Filtrar por descrição do sistema",
     *         @Schema(type="string")
     *     ),
     *     @Parameter(
     *         name="filter[sigla]",
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
     *                         @Property(property="data", ref="#/components/schemas/SistemasPaginateResponse")
     *                     )
     *                 }
     *             )
     *         )
     *     ),
     *     @Response(response="401",description="Não autorizado"),
     *     @Response(response="403",description="Sem permissão de acesso")
     * )
     *
     * @param SistemaIndexRequest $request
     * @return JsonResponse
     */
    public function index(SistemaIndexRequest $request)
    {
        $sistemas = QueryBuilder::for(Sistema::class, $request)
            ->allowedFilters([
                'descricao', 'sigla', 'email', 'status',
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
            ->allowedSorts('descricao', 'sigla', 'email', 'url', 'status')
            ->paginate(50)
            ->appends(request()->query());

        return response()->json(['success' => true, 'code' => 200, 'data' => $sistemas], 200);
    }

    /**
     * @Get(
     *     path="/sistemas/historico/{id}",
     *     tags={"Sistemas"},
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
     *                         @Property(property="data", ref="#/components/schemas/SistemaProperty")
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
    public function historico($id)
    {
        $sistema = $this->sistema
            ->with('createdBy')
            ->with('updatedBy')
            ->with('controles')
            ->with('controles.user')
            ->findOrFail($id);

        return response()->json(['success' => true, 'code' => 200, 'data' => $sistema], 200);
    }

    /**
     * @Get(
     *     path="/sistemas/{id}",
     *     tags={"Sistemas"},
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
     *                         @Property(property="data", ref="#/components/schemas/SistemaProperty")
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
    public function show($id)
    {
        $sistema = $this->sistema
            ->with('createdBy')
            ->with('updatedBy')
            ->with('controle')
            ->with('controle.user')
            ->findOrFail($id);

        return response()->json(['success' => true, 'code' => 200, 'data' => $sistema], 200);
    }

    /**
     * @Post(
     *     path="/sistemas",
     *     tags={"Sistemas"},
     *     summary="Criar novo sistema.",
     *     security={{ "apiAuth": {} }},
     *     @Parameter(
     *         name="status",
     *         in="query",
     *         description="Status do Sistema",
     *         required=true,
     *         @Schema(type="string", enum={"ativo", "cancelado"})
     *     ),
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"descricao", "sigla"},
     *                 @Property(property="descricao", type="string", description="Descrição"),
     *                 @Property(property="sigla", type="string", description="Sigla"),
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
     * @param SistemaStoreRequest $request
     * @return JsonResponse
     */
    public function store(SistemaStoreRequest $request)
    {
        $this->sistema->create($request->all());

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }

    /**
     * @Put(
     *     path="/sistemas/{id}",
     *     tags={"Sistemas"},
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
     *         @Schema(type="string", enum={"ativo", "cancelado"})
     *     ),
     *     @RequestBody(
     *         @MediaType(
     *             mediaType="application/json",
     *             @Schema(
     *                 required={"descricao", "sigla"},
     *                 @Property(property="descricao", type="string", description="Descrição"),
     *                 @Property(property="sigla", type="string", description="Sigla"),
     *                 @Property(property="email", type="string", description="E-mail"),
     *                 @Property(property="url", type="string", description="Url"),
     *                 @Property(property="justificativa", type="string", description="Justificativa")
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
     * @param SistemaUpdateRequest $request
     * @return JsonResponse
     */
    public function update($id, SistemaUpdateRequest $request)
    {
        $sistema = $this->sistema->findOrFail($id);

        $sistema->update($request->all());

        if ($request->has('justificativa')) {
            $this->controle->create([
                'sistema_id' => (int) $id,
                'user_id' => Auth::id(),
                'justificativa' => $request->input('justificativa')
            ]);
        }

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }

    /**
     * @Delete(
     *     path="/sistemas/{id}",
     *     tags={"Sistemas"},
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
    public function destroy($id)
    {
        $sistema = $this->sistema->findOrFail($id);

        $sistema->delete();

        return response()->json(['success' => true, 'code' => 200, 'data' => ['message' => 'Operação realizada com sucesso.']], 200);
    }
}
