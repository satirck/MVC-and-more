<?php

declare(strict_types=1);

namespace App\Repository;

interface RepositoryInterface
{
    function getAll();

    function getById(int $id);

    function save(string $entity);

    function delete(int $id);
}
