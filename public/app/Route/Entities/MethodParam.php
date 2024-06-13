<?php

declare(strict_types=1);

namespace App\Route\Entities;

class MethodParam
{
    public function __construct(
        public readonly string $typename,
        public readonly bool   $optional,
        public readonly string $name,
    )
    {
    }
}
