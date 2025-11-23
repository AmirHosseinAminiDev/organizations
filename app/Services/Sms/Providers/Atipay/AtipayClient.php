<?php

namespace App\Services\Sms\Providers\Atipay;

class AtipayClient
{
    public function send(array $payload): array
    {
        $url = config('atipay.base_url');
        $apiKey = config('atipay.api_key');

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-api-key: ' . $apiKey
            ],
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($curl);
        $curlError = curl_error($curl);
        curl_close($curl);

        if ($curlError) {
            return [
                'status' => 'error',
                'message' => 'cURL Error: ' . $curlError,
                'result' => null
            ];
        }

        return json_decode($response, true) ?: [];
    }
}
