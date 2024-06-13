<?php

declare(strict_types=1);

namespace App\Route\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD,)]
class MethodRouteAttribute
{
    public function __construct(
        protected string $httpMethod,
        protected string $urlPattern
    )
    {
    }

    public function getHttpMethod(): string
    {
        return $this->httpMethod;
    }

    public function getUrlPattern(): string
    {
        return $this->urlPattern;
    }
}
