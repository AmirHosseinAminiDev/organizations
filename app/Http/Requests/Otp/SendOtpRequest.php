<?php

namespace App\Http\Requests\Otp;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'phone' => 'required|string|regex:/[0-9]{10}/|exists:users,phone',
        ];
    }

    public function messages(): array
    {
        return [
            'phone.exists' => 'کاربری با این موبایل یافت نشد.',
            'phone.required' => 'شماره تلفن الزامی میباشد.',
            'phone.string' => 'نوع داده شماره موبایل نامعتبر.',
            'phone.regex' => 'فرمت موبایل وارد شده صحیح نیست.'
        ];
    }

    public function getPhone()
    {
        return $this->validated('phone');
    }
}
