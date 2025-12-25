<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

abstract class BaseApiController extends Controller
{
    /**
     * Validate pagination parameters and return paginated query.
     */
    protected function paginate(Request $request, $query, int $defaultPerPage = 15): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        $perPage = min(100, max(1, (int) $request->input('per_page', $defaultPerPage)));
        $page = max(1, (int) $request->input('page', 1));

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Return success response.
     */
    protected function success(mixed $data = null, string $message = 'OK', int $status = 200, ?array $meta = null): JsonResponse
    {
        return ApiResponse::ok($data, $message, $status, $meta);
    }

    /**
     * Return created response.
     */
    protected function created(mixed $data = null, string $message = 'Created', ?array $meta = null): JsonResponse
    {
        return ApiResponse::created($data, $message, $meta);
    }

    /**
     * Return no content response.
     */
    protected function noContent(string $message = 'No Content'): JsonResponse
    {
        return ApiResponse::noContent($message);
    }

    /**
     * Return error response.
     */
    protected function error(string $message = 'Error', int $status = 400, mixed $errors = null, ?array $meta = null): JsonResponse
    {
        return ApiResponse::error($message, $status, $errors, $meta);
    }

    /**
     * Return paginated response.
     */
    protected function paginated($paginator, ?string $resourceClass = null, string $message = 'OK'): JsonResponse
    {
        return ApiResponse::paginated($paginator, $resourceClass, $message);
    }
}

