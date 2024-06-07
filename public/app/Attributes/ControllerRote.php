<?php

declare(strict_types=1);

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class ControllerRote
{
    public function __construct(
        protected string $url,
        protected string $regPattern,
    )
    {
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    public function getRegPattern(): string
    {
        return $this->regPattern;
    }
}
