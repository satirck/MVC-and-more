<?php

declare(strict_types=1);

namespace App\controllers;

use App\attributes\MethodRote;

class HomeController implements RestControllerInterface
{
    #[MethodRote('GET', '/')]
    public function index(): void
    {
        echo 'Hello World at home!<br>';
    }

}