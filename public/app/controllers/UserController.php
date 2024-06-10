<?php

declare(strict_types=1);

namespace App\controllers;

use App\attributes\{MethodRote};
use Exception;

class UserController implements RestControllerInterface
{
    protected array $users = [
        ['name' => 'Mikita', 'age' => 20],
        ['name' => 'John Doe', 'age' => 20],
        ['name' => 'Jane Doe', 'age' => 20],
    ];

    #[MethodRote('GET', '/users')]
    public function index(): void
    {
        echo 'Hi at users page<br>Users: ';
        print_r($this->users);
    }

    /**
     * @throws Exception
     */
    #[MethodRote('GET', '/users/{sId}')]
    public function getUserById(int $sId): void
    {
        $id = intval($sId);

        if (!array_key_exists($id, $this->users)) {
            throw new Exception('User not found');
        }

        echo 'Hi at user page<br>User: ';
        print_r($this->users[$id]);
    }

//    #[MethodRote('POST', '/users}')]
//    public function createUser(User $user): void
//    {
//        $id = intval($sId);
//
//        if (!array_key_exists($id, $this->users)) {
//            throw new Exception('User not found');
//        }
//
//        echo 'Hi at user page<br>User: ';
//        print_r($this->users[$id]);
//    }
}
