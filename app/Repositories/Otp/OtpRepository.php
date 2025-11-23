<?php

namespace App\Repositories\Otp;

use App\Models\Otp;

class OtpRepository
{
    public function generate(string $phone)
    {
        $otp = $this->generateOtp();
        return Otp::query()->create([
            'phone' => $phone,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(5),
        ]);
    }

    public function verify()
    {

    }

    protected function generateOtp()
    {
        return rand(100000, 999999);
    }
}
