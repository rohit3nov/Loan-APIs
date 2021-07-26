<?php
namespace App\Components\CoreComponent\Modules\User;

use App\User;

class UserRepository
{
    public function create(array $data) : User
    {
        return User::create($data);
    }
}