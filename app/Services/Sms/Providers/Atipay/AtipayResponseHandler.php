<?php

namespace App\Services\Sms\Providers\Atipay;

class AtipayResponseHandler
{
    protected static array $statusMessages = [
        200 => ['status' => 'success', 'message' => 'Request was successful'],
        201 => ['status' => 'success', 'message' => 'Resource created successfully'],
        400 => ['status' => 'error', 'message' => 'Invalid request'],
        401 => ['status' => 'error', 'message' => 'API key is not valid'],
        403 => ['status' => 'error', 'message' => 'Forbidden'],
        404 => ['status' => 'error', 'message' => 'Resource not found'],
        500 => ['status' => 'error', 'message' => 'Internal server error'],
    ];

    public static function make(array $responseArray): array
    {
        $statusCode = $responseArray[0]['statusCode'] ?? $responseArray['statusCode'] ?? null;

        if (!$statusCode) {
            return [
                'status' => 'error',
                'message' => 'No status code in response',
                'result' => $responseArray
            ];
        }

        return array_merge(
            self::$statusMessages[$statusCode] ??
            ['status' => 'error', 'message' => 'Unexpected status code: '.$statusCode],
            ['response' => $responseArray]
        );
    }
}
