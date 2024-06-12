<?php

declare(strict_types=1);

namespace App\Route\Controllers;

use App\Route\Attributes\{DomainKeyAttribute, MethodRouteAttribute};

#[DomainKeyAttribute('/')]
class HomeRouteController implements RouteControllerInterface
{
    #[MethodRouteAttribute('GET', '/')]
    public function index(): void
    {
        echo 'Hello World at home!<br>';
    }

}
