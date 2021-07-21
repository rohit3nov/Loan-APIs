<?php

namespace App\Components\CoreComponent\Modules\Loan;

use App\Rules\RepaymentFrequencyTypeRule;
use Illuminate\Foundation\Http\FormRequest;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class LoanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return static::staticRules();
    }

    public static function staticRules()
    {
        return [
            "amount" => "required|numeric|min:1",
            "duration" => "required|numeric|min:1",
            // "repayment_frequency" => ["required", "numeric", new RepaymentFrequencyTypeRule()],
            "interest_rate" => "required|numeric",
            "date_contract_start" => "required",
        ];
    }

    /**
     * Get the validation custom error message.
     *
     * @return array
     */
    public function messages()
    {
        return static::staticMessages();
    }
    public static function staticMessages()
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
}
