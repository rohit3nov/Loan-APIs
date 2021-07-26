<?php

namespace App\Components\CoreComponent\Modules\Repayment\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RepaymentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'loan_id' => 'required|exists:loans,id',
            'amount' => 'required|numeric',
            'payment_status' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'loan_id.required' => trans('default.repayment_loan_id_required'),
            'loan_id.exists' => trans('default.repayment_loan_not_found'),
            'amount.required' => trans('default.repayment_amount_required'),
            'amount.numeric' => trans('default.repayment_amount_must_numeric'),
            'payment_status.required' => trans('default.repayment_status_id_required'),
            'payment_status.numeric' => trans('default.repayment_status_id_must_numeric'),
            'payment_status.required' => trans('default.repayment_due_date_required'),
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
