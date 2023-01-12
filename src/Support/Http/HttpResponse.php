<?php

namespace Support\Http;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\Response as StatusCode;

class HttpResponse
{
    public static function ok(mixed $data): JsonResponse
    {
        return Response::json(
            data: $data,
            status: StatusCode::HTTP_OK
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function created(?string $message): JsonResponse
    {
        return Response::json(
            data: ['status' => 'success', 'message' => $message ?? 'ok'],
            status: StatusCode::HTTP_CREATED
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function clientError(?string $message): JsonResponse
    {
        return Response::json(
            data: ['status' => 'error', 'message' => $message ?? 'Bad Request'],
            status: StatusCode::HTTP_BAD_REQUEST
        );

    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function unauthorized(?string $message): JsonResponse
    {
        return Response::json(
            data: ['status' => 'error', 'message' => $message ?? 'Unauthorized'],
            status:  StatusCode::HTTP_UNAUTHORIZED
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function forbidden(?string $message): JsonResponse
    {
        return Response::json(
            data: ['status' => 'error', 'message' => $message ?? 'Forbidden'],
            status: StatusCode::HTTP_FORBIDDEN
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function notFound(?string $message): JsonResponse
    {
        return Response::json(
            data: ['status' => 'error', 'message' => $message ?? 'Not Found'],
            status: StatusCode::HTTP_NOT_FOUND
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function conflict(?string $message): JsonResponse
    {
        return Response::json(
            data: ['status' => 'error', 'message' => $message ?? 'Conflict'],
            status: StatusCode::HTTP_CONFLICT
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function tooMany(?string $message): JsonResponse
    {
        return Response::json(
            data: ['status' => 'error', 'message' => $message ?? 'Too Many Requests'],
            status: StatusCode::HTTP_TOO_MANY_REQUESTS
        );
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public static function fail(?string $message): JsonResponse
    {
        return Response::json(
            data: ['status' => 'error', 'message' => $message ?? 'Internal Server Error'],
            status: StatusCode::HTTP_INTERNAL_SERVER_ERROR
        );
    }

}
