<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;

abstract class BaseApiController extends Controller
{
    protected function successResponse($data = null, string $message = 'Success', int $statusCode = 200)
    {
        return ApiResponse::success($data, $message, $statusCode);
    }

    protected function errorResponse(string $message = 'Error occurred', int $statusCode = 400, $errors = null)
    {
        return ApiResponse::error($message, $statusCode, $errors);
    }

    protected function paginatedResponse($data, string $message = 'Success')
    {
        return ApiResponse::paginated($data, $message);
    }
}