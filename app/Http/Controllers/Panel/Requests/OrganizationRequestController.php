<?php

namespace App\Http\Controllers\Panel\Requests;

use App\Enum\OrganizationEndpoints;
use App\Enum\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrganizationRequestController extends Controller
{
    public function getChargeAccountsList()
    {
        $authUser = Auth::user();

        // فقط مدیر سازمان دسترسی دارد
        if ($authUser->role->name !== UserRoleEnum::ORGANIZATION_ADMIN->value) {
            abort(403, 'فقط مدیر سازمان به این بخش دسترسی دارد.');
        }

        // لیست کدملی کاربران سازمان با نقش کاربر سازمان
        $nationalCodes = User::query()
            ->where('organization_id', $authUser->organization_id)
            ->whereHas('role', function ($q) {
                $q->where('name', UserRoleEnum::ORGANIZATION_USER->value);
            })
            ->whereNotNull('national_code')
            ->pluck('national_code')
            ->toArray();

        if (empty($nationalCodes)) {
            return view('panel.requests.charge-accounts', [
                'accounts' => [],
            ])->withErrors([
                'no_users' => 'هیچ کاربر سازمانی با کد ملی ثبت‌شده‌ای برای این سازمان یافت نشد.',
            ]);
        }

        // درخواست به API: گرفتن لیست حساب‌های شارژ
        $response = Http::withOptions([
            'verify' => false, // فقط برای محیط توسعه
        ])->post(
            config('organization.base_url') . OrganizationEndpoints::GET_CHARGE_ACCOUNT_REQUESTS->value,
            [
                'national_codes' => $nationalCodes,
            ]
        );

        if (!$response->successful()) {
            return back()->withErrors([
                'api' => 'خطا در ارتباط با سرویس حساب شارژ. لطفاً مجدداً تلاش کنید.',
            ]);
        }

        $data = $response->json('data') ?? [];

        // مپ کردن نام و نام‌خانوادگی از جدول users براساس کدملی
        $users = User::whereIn('national_code', $nationalCodes)->get()->keyBy('national_code');

        $accounts = [];

        foreach ($data as $item) {
            $nationalCode = $item['national_code'] ?? null;
            $user = $nationalCode ? ($users[$nationalCode] ?? null) : null;

            $item['name'] = $user->name ?? '';
            $item['last_name'] = $user->last_name ?? '';

            $accounts[] = $item;
        }

        return view('panel.requests.charge-accounts', compact('accounts'));
    }

    public function updateChargeAccountRequestStatus(Request $request)
    {
        $authUser = Auth::user();

        if ($authUser->role->name !== UserRoleEnum::ORGANIZATION_ADMIN->value) {
            abort(403, 'فقط مدیر سازمان به این بخش دسترسی دارد.');
        }

        $data = $request->validate([
            'uid' => 'required|string',
            'type' => 'required|in:accept,reject',
        ]);

        $response = Http::withOptions([
            'verify' => false,
        ])->post(
            config('organization.base_url') . OrganizationEndpoints::UPDATE_CHARGE_ACCOUNT_REQUEST_STATUS->value,
            $data
        );

        if (!$response->ok()) {
            return back()->withErrors([
                'api' => 'خطا در برقراری ارتباط با سرویس. لطفاً دوباره تلاش کنید.',
            ]);
        }

        $json = $response->json();

        if (isset($json['success']) && $json['success'] === true) {
            $message = $data['type'] === 'accept'
                ? 'درخواست شارژ حساب با موفقیت تایید شد.'
                : 'درخواست شارژ حساب با موفقیت رد شد.';

            return back()->with('success', $message);
        }

        return back()->withErrors([
            'api' => $json['message'] ?? 'بروزرسانی وضعیت درخواست ناموفق بود.',
        ]);
    }

    public function getReissueCardRequestsList()
    {
        $authUser = Auth::user();

        if ($authUser->role->name !== UserRoleEnum::ORGANIZATION_ADMIN->value) {
            abort(403, 'فقط مدیر سازمان به این بخش دسترسی دارد.');
        }

        $nationalCodes = User::query()
            ->where('organization_id', $authUser->organization_id)
            ->whereHas('role', function ($q) {
                $q->where('name', UserRoleEnum::ORGANIZATION_USER->value);
            })
            ->whereNotNull('national_code')
            ->pluck('national_code')
            ->toArray();

        if (empty($nationalCodes)) {
            return view('panel.requests.reissue-card-requests', [
                'requests' => [],
            ])->withErrors([
                'no_users' => 'هیچ کاربر سازمانی با کد ملی ثبت‌شده‌ای برای این سازمان یافت نشد.',
            ]);
        }

        $response = Http::withOptions([
            'verify' => false,
        ])->post(
            config('organization.base_url') . OrganizationEndpoints::GET_REISSUE_CARD_REQUESTS->value,
            [
                'national_codes' => $nationalCodes,
            ]
        );

        if (!$response->successful()) {
            return back()->withErrors([
                'api' => 'خطا در ارتباط با سرویس درخواست‌های صدور مجدد کارت. لطفاً مجدداً تلاش کنید.',
            ]);
        }

        $data = $response->json('data') ?? [];

        $users = User::whereIn('national_code', $nationalCodes)->get()->keyBy('national_code');

        $requests = [];

        foreach ($data as $item) {
            $nationalCode = $item['national_code'] ?? null;
            $user = $nationalCode ? ($users[$nationalCode] ?? null) : null;

            $item['name'] = $user->name ?? '';
            $item['last_name'] = $user->last_name ?? '';

            $requests[] = $item;
        }

        return view('panel.requests.reissue-card-requests', compact('requests'));
    }


    public function updateReissueCardRequestStatus(Request $request)
    {
        $authUser = Auth::user();

        if ($authUser->role->name !== UserRoleEnum::ORGANIZATION_ADMIN->value) {
            abort(403, 'فقط مدیر سازمان به این بخش دسترسی دارد.');
        }

        $data = $request->validate([
            'uid' => 'required|string',
            'type' => 'required|in:accept,reject',
        ]);

        $response = Http::withOptions([
            'verify' => false,
        ])->post(
            config('organization.base_url') . OrganizationEndpoints::UPDATE_REISSUE_CARD_REQUEST_STATUS->value,
            $data
        );

        if (!$response->ok()) {
            return back()->withErrors([
                'api' => 'خطا در برقراری ارتباط با سرویس. لطفاً دوباره تلاش کنید.',
            ]);
        }

        $json = $response->json();

        if (isset($json['success']) && $json['success'] === true) {
            $message = $data['type'] === 'accept'
                ? 'درخواست صدور مجدد کارت با موفقیت تایید شد.'
                : 'درخواست صدور مجدد کارت با موفقیت رد شد.';

            return back()->with('success', $message);
        }

        return back()->withErrors([
            'api' => $json['message'] ?? 'بروزرسانی وضعیت درخواست صدور مجدد کارت ناموفق بود.',
        ]);
    }
}
