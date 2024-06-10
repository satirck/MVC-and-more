<?php

declare(strict_types=1);

namespace App\models;

use JsonSerializable;

abstract class AbstractEntity implements JsonSerializable
{
    protected int $id;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId()
        ];
    }

    abstract public static function fromJson(string $json): AbstractEntity;
}
