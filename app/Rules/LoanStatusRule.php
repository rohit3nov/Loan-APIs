<?php

namespace App\Rules;

use App\Components\CoreComponent\Modules\Loan\LoanStatus;
use Illuminate\Contracts\Validation\Rule;

/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */
class LoanStatusRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return LoanStatus::isValidStatus($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('default.loan_invalid_status');
    }
}
