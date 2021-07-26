<?php

namespace App\Components\CoreComponent\Modules\Repayment;

use App\Components\CoreComponent\Modules\Client\ClientResource;
use Illuminate\Http\Resources\Json\Resource;

/*
 * Author:  Rohit Pandita(rohit3nov@gmail.com)
 */
class RepaymentResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $repayment = $this->resource;
        $array = $repayment->toArray();
        $array['user_id'] = $repayment->loan->user->id;
        $array['loan'] = $repayment->loan;
        return $array;
    }
}
