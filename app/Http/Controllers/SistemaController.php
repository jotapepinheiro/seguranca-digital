<?php

namespace App\Http\Controllers;

use App\Sistema;
use App\Controle;
use Illuminate\Http\JsonResponse;
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
     * List all Sistemas or Filter data
     *
     * @param SistemaIndexRequest $request
     * @return mixed
     */
    public function index(SistemaIndexRequest $request)
    {
        $sistemas = QueryBuilder::for(Sistema::class, $request)
            ->allowedFilters([
                'descricao', 'sigla', 'email', 'status',
                'controles.user.name', 'controle.user.name',
                'controles.user.email', 'controle.user.email'
            ])
            ->allowedIncludes(['controle', 'controle.user', 'controles', 'controles.user'])
            ->defaultSort('-id')
            ->allowedSorts('descricao', 'sigla', 'email', 'url', 'status')
            ->paginate(50)
            ->appends(request()->query());

        return response()->json($sistemas);
    }

    /**
     * List Sistema whith all controles
     *
     * @param $id
     * @return mixed
     */
    public function historico($id)
    {
        $sistema = $this->sistema
            ->with('created_by')
            ->with('updated_by')
            ->with('controles')
            ->with('controles.user')
            ->findOrFail($id);

        return response()->json($sistema);
    }

    /**
     * List Sistema whith last controle
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $sistema = $this->sistema
            ->with('created_by')
            ->with('updated_by')
            ->with('controle')
            ->with('controle.user')
            ->findOrFail($id);

        return response()->json($sistema);
    }

    /**
     * Create new Sistema
     *
     * @param SistemaStoreRequest $request
     * @return JsonResponse
     */
    public function store(SistemaStoreRequest $request)
    {
        $this->sistema->create($request->all());

        return response()->json(['data' => ['message' => 'Operação realizada com sucesso.']]);
    }

    /**
     * Updates Sistema by ID, creates a new controle case exists parameter justificativa
     *
     * @param $id
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

        return response()->json(['data' => ['message' => 'Operação realizada com sucesso.']]);
    }

    /**
     * Delete Sistema by ID and Controle in cascade
     *
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $sistema = $this->sistema->findOrFail($id);

        $sistema->delete();

        return response()->json(['data' => ['message' => 'Operação realizada com sucesso.']]);
    }
}
