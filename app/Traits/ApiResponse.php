<?php


namespace App\Traits;


use Illuminate\Http\JsonResponse;


trait ApiResponse
{
    protected function ok(mixed $data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json([
            'statusCode' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }


    protected function created(mixed $data = null, string $message = 'Created'): JsonResponse
    {
        return $this->ok($data, $message, 201);
    }


    protected function fail(string $message = 'Bad Request', int $status = 400, mixed $data = null): JsonResponse
    {
        return response()->json([
            'statusCode' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }
}