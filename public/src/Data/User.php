<?php

declare(strict_types=1);

namespace App\Data;
class User
{

    public function __construct(
        protected string $name,
        protected int    $age
    )
    {
    }

    public function __toString(): string
    {
        return sprintf('{[name=%s][age=%d]}%s', $this->name, $this->age, PHP_EOL);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }
}
