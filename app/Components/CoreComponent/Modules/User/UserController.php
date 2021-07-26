<?php
namespace App\Components\CoreComponent\Modules\User;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function apiRegisterUser(Requests\SignUpApiRequest $request) : JsonResponse
    {
        try {
            $user = $this->userService->createUser($request->only('name','email','password'));
            $token = $this->userService->generateOAuthAccessToken($user);
        } catch (\Exception $e) {
            Log::error(__FUNCTION__.': '.$e->getMessage());
            return response()->json(['status'=>'failure','message' => 'Unable to register new user.Technical issue occured. Please try again after some time!'],500);
        }
        return response()->json(['status'=>'success','user' => $user, 'access_token' => $token],200);
    }

    public function apiLoginUser(Requests\LoginApiRequest $request) : JsonResponse
    {
        try {
            $user = $this->userService->login($request->only('email','password'));
            $token = $this->userService->generateOAuthAccessToken($user);
        } catch (\Exception $e) {
            return response()->json(['status'=>'failure','message' => $e->getMessage()],401);
        }
        return response()->json(['status'=>'success','user' => $user, 'access_token' => $token],200);
    }
}
