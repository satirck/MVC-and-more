<?php

declare(strict_types=1);

namespace App\Route\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ModelBindingAttribute
{
    public function __construct(
        public readonly string $model,
        public readonly string $realisation,
    )
    {
    }
}
