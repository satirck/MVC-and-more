<?php

declare(strict_types=1);

namespace App\Repository;

use App\Models\AbstractEntity;
use App\Repository\Exceptions\EntityNotFoundException;
use App\Repository\Exceptions\FileReadException;
use App\Repository\Exceptions\FileWriteException;
use InvalidArgumentException;
use RuntimeException;

abstract class AbstractEntityRepository implements RepositoryInterface
{
    public function __construct(
        protected string $filePath,
        protected string $entityClass,
    )
    {
        if (!is_subclass_of($entityClass, AbstractEntity::class)) {
            throw new InvalidArgumentException("$entityClass must be a subclass of AbstractEntity.");
        }
    }

    public function getAll(): array
    {
        $entitiesData = $this->readFromFile();

        $entities = [];
        foreach ($entitiesData as $entityData) {
            $entities[] = $this->deserializeEntity(json_encode($entityData));
        }

        return $entities;
    }

    /**
     * @throws EntityNotFoundException
     */
    public function getById(int $id): ?AbstractEntity
    {
        $entities = $this->getAll();

        foreach ($entities as $entity) {
            if ($entity->getId() === $id) {
                return $entity;
            }
        }

        throw new EntityNotFoundException("Entity with ID $id not found.");
    }

    /**
     * @throws
     */
    public function save(string $entity): string
    {
        $entities = $this->getAll();

        if (!empty($entities)) {
            $lastEntity = end($entities);
            $newId = $lastEntity->getId() + 1;
        } else {
            $newId = 1;
        }

        $entityObject = $this->deserializeEntity($entity);
        $entityObject->setId($newId);

        $entities[] = $entityObject;

        try {
            $this->writeToFile($entities);
        } catch (FileWriteException $e) {
            throw new RuntimeException("Failed to save entity: " . $e->getMessage(), $e->getCode(), $e);
        }

        return json_encode($entityObject);
    }

    public function delete(int $id): void
    {
        $entities = $this->getAll();
        $entities = array_filter($entities, function ($entity) use ($id) {
            return $entity->getId() !== $id;
        });

        try {
            $this->writeToFile($entities);
        } catch (FileWriteException $e) {
            throw new RuntimeException("Failed to delete entity: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    private function readFromFile(): array
    {
        if (!file_exists($this->filePath)) {
            throw new FileReadException("File not found: $this->filePath");
        }

        $jsonData = file_get_contents($this->filePath);

        if ($jsonData === false) {
            throw new FileReadException("Failed to read file: $this->filePath");
        }

        return json_decode($jsonData, true);
    }

    private function writeToFile(array $entities): void
    {
        $jsonData = json_encode($entities, JSON_PRETTY_PRINT);

        if ($jsonData === false) {
            throw new FileWriteException("Failed to encode data to JSON.");
        }

        $result = file_put_contents($this->filePath, $jsonData);

        if ($result === false) {
            throw new FileWriteException("Failed to write data to file: $this->filePath");
        }
    }

    private function deserializeEntity(string $entity): AbstractEntity
    {
        $entityClass = $this->entityClass;

        if (is_subclass_of($entityClass, AbstractEntity::class)) {
            return $entityClass::fromJson($entity);
        }

        throw new InvalidArgumentException("$entityClass must be a subclass of AbstractEntity.");
    }
}
