<?php

declare(strict_types=1);

namespace App\controllers;

use App\Attributes\{ControllerRote, MethodRote};

#[ControllerRote('/','/^\/$/')]
class HomeController
{
    #[MethodRote('GET', '/^\/$/')]
    public function index(): void
    {
        echo 'Hello World at home!<br>';
    }

    public function index2(): void{

    }
}