<?php

declare(strict_types=1);

namespace App\repository;

use RuntimeException;
use InvalidArgumentException;
use App\models\AbstractEntity;

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

    public function getAllEntities(): array
    {
        $entitiesData = $this->readFromFile();

        $entities = [];
        foreach ($entitiesData as $entityData) {
            $entities[] = $this->deserializeEntity(json_encode($entityData));
        }

        return $entities;
    }

    public function getEntityById(int $id): ?AbstractEntity
    {
        $entities = $this->getAllEntities();

        foreach ($entities as $entity) {
            if ($entity->getId() === $id) {
                return $entity;
            }
        }

        throw new EntityNotFoundException("Entity with ID $id not found.");
    }

    public function saveEntity(string $entity): void
    {
        $entityObject = $this->deserializeEntity($entity);

        $entities = $this->getAllEntities();

        foreach ($entities as $existingEntity) {
            if ($existingEntity->getId() === $entityObject->getId()) {
                throw new DuplicateEntityException("Entity with ID {$entityObject->getId()} already exists.");
            }
        }

        $entities[] = $entityObject;

        try {
            $this->writeToFile($entities);
        } catch (FileWriteException $e) {
            throw new RuntimeException("Failed to save entity: " . $e->getMessage(), $e->getCode(), $e);
        }
    }

    public function deleteEntity(int $id): void
    {
        $entities = $this->getAllEntities();
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


            return $entityClass::fromJson($entity);


    }
}
