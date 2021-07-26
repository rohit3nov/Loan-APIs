<?php

namespace App\Components\CoreComponent\Modules\User\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

/*
 * Author: Rohit Pandita(rohit3nov@gmail.com)
 */
class LoginApiRequest extends FormRequest
{
    public function rules()
    {
        return [
            'email' => 'email|required',
            'password' => 'required'
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
