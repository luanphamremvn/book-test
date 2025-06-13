<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

trait ResponseTrait
{

    /**
     * @param string $message
     * @param array|null $data
     * @param int $statusCode
     * @return JsonResponse
     */
    public function success(string $message, array|null $data = null, int $statusCode = ResponseAlias::HTTP_OK): JsonResponse
    {
        return response($statusCode)->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * @param string $message
     * @param int $errorCode
     * @param int $statusCode
     * @return JsonResponse
     */
    public function error(string $message, int $errorCode, int $statusCode = ResponseAlias::HTTP_BAD_REQUEST): JsonResponse
    {
        return response($statusCode)->json([
            'success' => false,
            'message' => $message,
            'error_code' => $errorCode
        ]);
    }
}
