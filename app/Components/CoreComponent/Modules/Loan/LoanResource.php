<?php

namespace App\Components\CoreComponent\Modules\Loan;

use App\Components\CoreComponent\Modules\Repayment\RepaymentCollection;
use Illuminate\Http\Resources\Json\Resource;

/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */
class LoanResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $loan = $this->resource;
        $array = $loan->toArray();
        $array['loan_id'] = $loan->id;
        $array['repayments'] = (new RepaymentCollection($loan->repayments))->toArray($request);
        return $array;
    }
}
