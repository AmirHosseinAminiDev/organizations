<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Otp\SendOtpRequest;
use App\Repositories\Otp\OtpRepository;
use App\Services\Sms\SmsInterface;
use Illuminate\Support\Facades\Log;

class RequestOtpController extends Controller
{
    public function __construct(
        protected OtpRepository $otpRepository,
        protected SmsInterface $sms
    ) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(SendOtpRequest $request)
    {
        $phone = $request->getPhone();
        $otp = $this->otpRepository->generate($phone);
        $message = "کد ورود شما : \n $otp";

        $sendSmsResult = $this->sms->send($phone, $message);

        Log::debug('Sms Result', ['result' => $sendSmsResult]);

        if ($sendSmsResult['status'] === 'success') {
            return redirect()->route('otp.verify.show')
                ->with('phone', $phone);
        }

        return back()->withErrors([
            'sms' => $sendSmsResult['message'] ?? 'خطا در ارسال پیامک'
        ]);
    }
}
