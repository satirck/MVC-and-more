<?php

declare(strict_types=1);

namespace App\Route\Entities;

class RouteEntity
{
    public function __construct(
        public readonly string $controller,
        public readonly string $action,
        public readonly string $urlPattern
    )
    {
    }
}
