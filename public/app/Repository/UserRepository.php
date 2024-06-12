<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\User;

class UserRepository extends AbstractEntityRepository
{
    public function __construct()
    {
        parent::__construct('app/Storage/users.json', User::class);
    }
}
