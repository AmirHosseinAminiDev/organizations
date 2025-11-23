<?php

namespace App\Services\Sms;

use App\Services\Sms\Providers\Atipay\AtipayClient;
use App\Services\Sms\Providers\Atipay\AtipayPayload;
use App\Services\Sms\Providers\Atipay\AtipayResponseHandler;

class AtipaySms implements SmsInterface
{
    protected AtipayClient $client;

    public function __construct(AtipayClient $client)
    {
        $this->client = $client;
    }

    public function send(string $to, string $message, ?int $customer_id = null): array
    {
        try {
            $payload = AtipayPayload::make($to, $message, $customer_id);

            $responseArray = $this->client->send($payload);

            if (isset($responseArray['status']) && $responseArray['status'] === 'error' && !isset($responseArray['response'])) {
                return $responseArray;
            }

            return AtipayResponseHandler::make($responseArray);

        } catch (\Throwable $e) {
            return [
                'status' => 'error',
                'message' => 'Request Exception: ' . $e->getMessage(),
                'result' => null
            ];
        }
    }
}
