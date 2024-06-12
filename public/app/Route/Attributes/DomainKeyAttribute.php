<?php

declare(strict_types=1);

namespace App\Route\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class DomainKeyAttribute
{
    public function __construct(
        public readonly string $domainKey
    )
    {
    }
}
