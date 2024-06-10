<?php

declare(strict_types=1);

namespace App\controllers;

use App\models\User;
use App\repository\EntityNotFoundException;
use App\repository\UserRepository;
use App\route\StatusError;
use App\attributes\{MethodRote};
use Exception;

class UserController implements RestControllerInterface
{

    protected UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    #[MethodRote('GET', '/users')]
    public function index(): void
    {
        echo 'Hi at users page<br>Users: ';

        $users = $this->userRepository->getAllEntities();
        print_r($users);
    }

    /**
     * @throws Exception
     */
    #[MethodRote('GET', '/users/{id}')]
    public function getUserById(int $id): void
    {
        try {
            $user = $this->userRepository->getEntityById($id);
            echo 'Hi at user page.<br>';
            print_r($user);
        }catch (EntityNotFoundException $exception){
            throw new StatusError(404, $exception->getMessage());
        }
    }

    #[MethodRote('POST', '/users')]
    public function createUser(User $user): void
    {
        $savedUser = $this->userRepository->saveEntity(json_encode($user));

        echo 'Saved user is: <br>';
        print_r($savedUser);
    }

}
