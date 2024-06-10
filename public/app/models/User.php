<?php

declare(strict_types=1);

namespace App\models;

use InvalidArgumentException;

class User extends AbstractEntity
{

    public function __construct(
        int              $id,
        protected string $name,
        protected string $email,
    )
    {
        parent::setId($id);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    public static function fromJson(string $json): User
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException('Invalid JSON data: ' . json_last_error_msg());
        }

        return new self(
            id: $data['id'],
            name: $data['name'],
            email: $data['email']
        );
    }
}
