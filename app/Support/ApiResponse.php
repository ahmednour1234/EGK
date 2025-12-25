<?php

namespace App\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ApiResponse
{
    /**
     * Return a successful JSON response.
     */
    public static function ok(
        mixed $data = null,
        string $message = 'OK',
        int $status = 200,
        ?array $meta = null
    ): JsonResponse {
        return self::response(true, $message, $data, $status, $meta);
    }

    /**
     * Return a created JSON response.
     */
    public static function created(
        mixed $data = null,
        string $message = 'Created',
        ?array $meta = null
    ): JsonResponse {
        return self::response(true, $message, $data, 201, $meta);
    }

    /**
     * Return a no content JSON response.
     */
    public static function noContent(string $message = 'No Content'): JsonResponse
    {
        return self::response(true, $message, null, 204);
    }

    /**
     * Return an error JSON response.
     */
    public static function error(
        string $message = 'Error',
        int $status = 400,
        mixed $errors = null,
        ?array $meta = null
    ): JsonResponse {
        return self::response(false, $message, null, $status, $meta, $errors);
    }

    /**
     * Return a paginated JSON response.
     */
    public static function paginated(
        LengthAwarePaginator|AnonymousResourceCollection $paginator,
        ?string $resourceClass = null,
        string $message = 'OK'
    ): JsonResponse {
        $data = $paginator->items();
        
        // Transform to resource collection if provided
        if ($resourceClass && class_exists($resourceClass)) {
            $data = $resourceClass::collection($data);
            $data = $data->resolve(request());
        }

        $meta = [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'from' => $paginator->firstItem(),
            'to' => $paginator->lastItem(),
            'has_more_pages' => $paginator->hasMorePages(),
            'links' => [
                'first' => $paginator->url(1),
                'last' => $paginator->url($paginator->lastPage()),
                'prev' => $paginator->previousPageUrl(),
                'next' => $paginator->nextPageUrl(),
            ],
        ];

        return self::response(true, $message, $data, 200, $meta);
    }

    /**
     * Build the unified JSON response structure.
     */
    protected static function response(
        bool $success,
        string $message,
        mixed $data = null,
        int $status = 200,
        ?array $meta = null,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        // Add debug info in local environment
        if (config('app.debug') && app()->environment('local')) {
            $response['meta']['debug'] = [
                'environment' => app()->environment(),
                'timestamp' => now()->toIso8601String(),
            ];
        }

        return response()->json($response, $status);
    }
}

