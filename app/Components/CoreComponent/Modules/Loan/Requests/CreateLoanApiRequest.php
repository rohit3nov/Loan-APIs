<?php

namespace App\Components\CoreComponent\Modules\Loan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class CreateLoanApiRequest extends FormRequest
{
    public function rules()
    {
        return [
            "amount" => "required|numeric|min:1",
            "duration" => "required|numeric|min:1",
            "interest_rate" => "required|numeric",
            "date_contract_start" => "required",
        ];
    }

    public function messages()
    {
        return [
            "amount.required" => trans("default.loan_amount_required"),
            "amount.numeric" => trans("default.loan_amount_must_numeric"),
            "amount.minx" => trans("default.loan_amount_must_greater_1"),
            "duration.required" => trans("default.loan_duration_required"),
            "duration.numeric" => trans("default.loan_duration_must_numeric"),
            "duration.min" => trans("default.loan_duration_must_greater_1"),
            "interest_rate.required" => trans("default.loan_int_rate_required"),
            "interest_rate.numeric" => trans("default.loan_int_rate_must_numeric"),
            "date_contract_start.required" => trans("default.loan_cont_start_required"),
        ];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            "status" => "error",
            "message" => trans("default.validation_error"),
            "errors" => $validator->errors()->first(),
        ], 400));
    }
}
