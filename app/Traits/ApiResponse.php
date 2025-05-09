<?php

namespace App\Traits;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

trait ApiResponse
{
    protected function successResponse(string $message, $data, int $status = 200, ?array $meta = null): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($meta !== null) {
            $response['meta'] = $meta;
        }

        return response()->json($response, $status);
    }


    protected function errorResponse(string $message, array $errors = [], int $status = 400, ?Exception $exception = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        if ($exception) {
            Log::error("API Error: {$message} - {$exception->getMessage()}", [
                'exception' => $exception,
                'trace' => $exception->getTrace(),
            ]);

            if (config('app.debug')) {
                $response['debug'] = [
                    'exception' => get_class($exception),
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                ];
            }
        }

        return response()->json($response, $status);
    }
}
