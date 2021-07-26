<?php

namespace App\Components\CoreComponent\Modules\Loan\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class UpdateLoanApiRequest extends FormRequest
{
    public function rules()
    {
        return [
            "loan_id" => "required|numeric",
            "status"  => ["required","numeric",new \App\Rules\LoanStatusRule()],
        ];
    }

    public function messages()
    {
        return [];
    }

    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(response()->json([
            "status" => "error",
            "message" => trans("default.validation_error"),
            "errors" => $validator->errors()->first(),
        ], 400));
    }
}
