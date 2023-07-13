<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait RestExceptionHandlerTrait
{

    /**
     * Creates a new JSON response based on exception type.
     *
     * @param Exception $error
     * @return JsonResponse
     */
    protected function getJsonResponseForException(Exception $error): JsonResponse
    {
        return match (true) {
            $this->isModelNotFoundException($error) => $this->modelNotFound(),
            default => $this->badRequest(),
        };
    }

    /**
     * Returns json response for generic bad request.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function badRequest(string $message='O pedido não pode ser entendido', int $statusCode=400): JsonResponse
    {
        return $this->jsonResponse(['status' => false, 'code' => $statusCode, 'message' => $message], $statusCode);
    }

    /**
     * Returns json response for Eloquent model not found exception.
     *
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function modelNotFound(string $message='Registro não encontrado', int $statusCode=404): JsonResponse
    {
        return $this->jsonResponse(['status' => false, 'code' => $statusCode, 'message' => $message], $statusCode);
    }

    /**
     * Returns json response.
     *
     * @param array|null $payload
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function jsonResponse(array $payload=null, int $statusCode=404): JsonResponse
    {
        $payload = $payload ?: [];

        return response()->json($payload, $statusCode);
    }

    /**
     * Determines if the given exception is an Eloquent model not found.
     *
     * @param Exception $error
     * @return bool
     */
    protected function isModelNotFoundException(Exception $error): bool
    {
        return $error instanceof ModelNotFoundException;
    }

}
