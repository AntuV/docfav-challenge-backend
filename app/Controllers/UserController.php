<?php

namespace App\Controllers;

use App\Exceptions\UserNotCreatedException;
use App\Models\User;
use App\Repositories\UserRepository;

class UserController extends BaseController
{
    public function post(): string
    {
        $error = $this->validateRequiredFields(['first_name', 'last_name', 'email', 'password'], $_POST);
        if ($error) {
            return $error;
        }

        $user = new User(
            null,
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['password']
        );

        $repository = new UserRepository();

        try {
            $repository->create($user);
        } catch (UserNotCreatedException $e) {
            $this->sendOutput(['message' => $e->getMessage()], ['HTTP/1.1 500 Internal Server Error']);
        }

        return $this->sendOutput($user);
    }

    public function get(): string
    {
        $repository = new UserRepository();

        $users = $repository->getAll();

        return $this->sendOutput($users);
    }
}
