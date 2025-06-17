<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    /**
     * Generate a success response.
     *
     * @param string $message
     * @param array|null $data
     * @param int $statusCode
     * @return JsonResponse
     * @throws Exception
     */
    public function success(string $message, array|null $data = null, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    /**
     * Generate an error response.
     *
     * @param string $message
     * @param int $errorCode
     * @param int $statusCode
     * @return JsonResponse
     * @throws Exception
     */
    public function error(string $message, int $errorCode, int $statusCode = Response::HTTP_BAD_REQUEST): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'error_code' => $errorCode
        ], $statusCode);
    }
}
