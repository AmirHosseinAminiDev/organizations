<?php

namespace App\Services\Sms;

interface SmsInterface
{
    public function send(string $to, string $message, ?int $customer_id = null): array;
}
