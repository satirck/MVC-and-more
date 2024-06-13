<?php

declare(strict_types=1);

namespace App\Route\Entities;

class ActionEntity
{
    public function __construct(
        public readonly string $action,
        public readonly string $urlPattern,
    )
    {
    }
}
