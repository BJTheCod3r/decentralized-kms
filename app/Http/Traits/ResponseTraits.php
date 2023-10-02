<?php

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait ResponseTraits
{
    /**
     * @param ResourceCollection $collection
     * @param string $message
     * @return ResourceCollection
     */
    public function collectionResponse(ResourceCollection $collection, $additional = [], string $message = 'Successful'): ResourceCollection
    {
        return $collection->additional(array_merge([
            'message' => $message
        ], $additional));
    }

    /**
     * @param $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function successResponse($data = [], string $message = '', int $statusCode = 200): JsonResponse
    {
        return response()->json(['message' => $message, 'data' => $data], $statusCode);
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function errorResponse(string $message = '', int $statusCode = 400): JsonResponse
    {
        return response()->json(['message' => $message], $statusCode);
    }

    /**
     * Send error response with data, for special cases.
     * @param $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    public function errorResponseWithData($data = [], string $message = '', int $statusCode = 400): JsonResponse
    {
        return response()->json(['message' => $message, 'data' => $data], $statusCode);
    }
}
