<?php

namespace App\Components\CoreComponent\Modules\User;

use Illuminate\Http\Request;
// use App\Http\Controllers\Controller;
use Illuminate\Routing\Controller;
use App\User;
use Validator;

class UserController extends Controller
{
    public function apiRegisterUser(Request $request)
    {
        $validator = Validator::make($request->all(), UserRequest::staticRules(), UserRequest::staticMessages());
        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => trans("default.validation_error"),
                "errors" => $validator->errors()->first(),
            ], 400);
        }

        $request['password'] = bcrypt($request->password);

        $user = User::create($request->only('name','email','password'));

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([ 'user' => $user, 'access_token' => $accessToken]);
    }

    public function apiLoginUser(Request $request)
    {
        $validator = Validator::make($request->all(), ['email' => 'email|required','password' => 'required'],[]);
        if ($validator->fails()) {
            return response()->json([
                "status" => "error",
                "message" => trans("default.validation_error"),
                "errors" => $validator->errors()->first(),
            ], 400);
        }

        if (!auth()->attempt($request->only('email','password'))) {
            return response(['message' => 'Invalid Credentials']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['user' => auth()->user(), 'access_token' => $accessToken]);

    }
}
