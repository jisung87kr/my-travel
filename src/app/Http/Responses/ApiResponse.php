<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiResponse
{
    /**
     * Return a success response.
     */
    public static function success(mixed $data = null, ?string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Return a created response.
     */
    public static function created(mixed $data = null, ?string $message = '리소스가 생성되었습니다.'): JsonResponse
    {
        return self::success($data, $message, 201);
    }

    /**
     * Return an error response.
     */
    public static function error(string $message, int $code = 400, mixed $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $code);
    }

    /**
     * Return a validation error response.
     */
    public static function validationError(mixed $errors, string $message = '입력값을 확인해주세요.'): JsonResponse
    {
        return self::error($message, 422, $errors);
    }

    /**
     * Return a forbidden response.
     */
    public static function forbidden(string $message = '권한이 없습니다.'): JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Return a not found response.
     */
    public static function notFound(string $message = '리소스를 찾을 수 없습니다.'): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Return a paginated response.
     */
    public static function paginated(LengthAwarePaginator $paginator, ?string $message = null): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ]);
    }

    /**
     * Return a deleted response.
     */
    public static function deleted(?string $message = '삭제되었습니다.'): JsonResponse
    {
        return self::success(null, $message);
    }

    /**
     * Return an unauthorized response.
     */
    public static function unauthorized(string $message = '인증이 필요합니다.'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Return a server error response.
     */
    public static function serverError(string $message = '서버 오류가 발생했습니다.'): JsonResponse
    {
        return self::error($message, 500);
    }
}
