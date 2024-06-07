<?php

declare(strict_types=1);

namespace App\controllers;

use App\Attributes\{ControllerRote, MethodRote};
use Exception;

#[ControllerRote('/users','/^\/users(\/\d+)?$/')]
class UserController
{
    protected array $users = [
        ['name' => 'Mikita', 'age' => 20],
        ['name' => 'John Doe', 'age' => 20],
        ['name' => 'Jane Doe', 'age' => 20],
    ];

    #[MethodRote('GET', '/^\/users$/')]
    public function index(): void
    {
        echo 'Hi at users page<br>Users: ';
        print_r($this->users);
    }

}
