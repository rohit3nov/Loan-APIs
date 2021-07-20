<?php

namespace App\Components\CoreComponent\Modules\Client;

use App\Components\CoreComponent\Modules\Loan\LoanCollection;
use Illuminate\Http\Resources\Json\Resource;

/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */
class ClientResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $client = $this->resource;
        $array = $client->toArray();
        $array['loans'] = (new LoanCollection($client->loans))->toArray($request);
        return $array;
    }
}
