<?php

declare(strict_types=1);

namespace App\repository;

interface RepositoryInterface
{
    function getAllEntities();

    function getEntityById(int $id);

    function saveEntity(string $entity);

    function deleteEntity(int $id);
}