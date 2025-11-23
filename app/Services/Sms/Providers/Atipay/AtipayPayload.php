<?php

namespace App\Services\Sms\Providers\Atipay;

class AtipayPayload
{
    public static function make(string $to, string $message, ?int $customer_id = null): array
    {
        $customer_id ??= random_int(1111, 9999) . now()->timestamp;

        return [[
            "srcNum" => config('atipay.src_num'),
            "recipient" => (string)$to,
            "body" => (string)$message,
            "customerId" => (int)("10" . time() . $customer_id . random_int(10, 99)),
            "type" => 1,
            "retryCount" => 2,
            "validityPeriod" => 10
        ]];
    }
}
