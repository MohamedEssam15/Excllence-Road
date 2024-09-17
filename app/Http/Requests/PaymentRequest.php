<?php

namespace App\Http\Requests;

use App\Models\Enrollment;
use App\Rules\CreditCard;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;

class PaymentRequest extends FormRequest
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
            'cardInfo' => ['required', 'array'],
            'cardInfo.cardHolderName' => ['required', 'string'],
            'cardInfo.cardNumber' => ['required', new CreditCard($this->cardInfo['cardExpDate'], $this->cardInfo['cardCvv'])],
            'cardInfo.cardExpDate' => ['required', 'regex:/^(0[1-9]|1[0-2])-\d{4}$/'],
            'cardInfo.cardCvv' => ['required', 'digits_between:3,4'],
            'addressInfo.address' => 'required|string|max:255',
            'addressInfo.city' => 'required|string|max:255',
            'addressInfo.country' => 'required|string|max:255',
            'paymentFor' => ['required', 'in:course,package'],
            'paymentForId' => [
                'required',
                'integer',
                'bail',
                function ($attribute, $value, $fail) {
                    if (request('paymnetFor') === 'course') {
                        if (!DB::table('courses')->where('id', $value)->exists()) {
                            $fail(__('response.courseNotFound'));
                        }
                    } elseif (request('paymnetFor') === 'package') {
                        if (!DB::table('packages')->where('id', $value)->exists()) {
                            $fail(__('response.packageNotFound'));
                        }
                    }
                },
            ],

        ];
    }

    protected function failedValidation(Validator $validator) // Use the correct Validator interface
    {
        throw new HttpResponseException(
            apiResponse('error', new \stdClass(), $validator->errors()->all(), 422)
        );
    }
}
