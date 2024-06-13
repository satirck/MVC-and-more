<?php

declare(strict_types=1);

namespace App\Route\Entities;

class ControllerEntity
{
    public function __construct(
        public string $controller,
        public string $domainKey,
    )
    {
    }
}
