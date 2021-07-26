<?php
namespace App\Components\CoreComponent\Modules\User;

use App\User;

class UserService
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function createUser(array $data) : User
    {
        $request['password'] = bcrypt($data['password']);
        return $this->userRepository->create($data);
    }

    public function generateOAuthAccessToken(User $user): string
    {
        return $user->createToken('authToken')->accessToken;
    }

    public function login(array $data)
    {
        if (!auth()->attempt($data)) {
            throw new \Exception('Unable to login. Invalid Credentials.');
        }
        return auth()->user();
    }

}