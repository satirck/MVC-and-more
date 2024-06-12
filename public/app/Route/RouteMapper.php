<?php

declare(strict_types=1);

namespace App\Route;

use App\Route\Exceptions\InvalidRouteArgumentException;
use App\Route\Attributes\{DomainKeyAttribute, MethodRouteAttribute};
use App\Route\Entities\ActionEntity;
use App\Route\Entities\ControllerEntity;
use App\Route\Entities\RouteEntity;
use App\Route\Exceptions\StatusErrorException;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class RouteMapper
{
    private array $routesControllers;
    private const REQUEST_INDEX = 'request';

    /**
     * @throws ReflectionException
     */
    public function __construct(
        protected string $controllersFolder
    )
    {
        $this->routesControllers = $this->getControllers();
    }

    private function getControllerFiles(): array
    {
        $returnFiles = [];

        if (is_dir($this->controllersFolder)) {
            $files = scandir($this->controllersFolder);
            foreach ($files as $file) {
                $filePath = $this->controllersFolder . DIRECTORY_SEPARATOR . $file;
                if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'php') {
                    $returnFiles[] = $filePath;
                }
            }
        }

        return $returnFiles;
    }

    /**
     * @return array of ControllerEntity
     * @throws ReflectionException
     */
    private function getControllersEntities(): array
    {
        $controllersEntities = [];
        $files = $this->getControllerFiles();

        foreach ($files as $file) {
            require_once $file;
            $classes = get_declared_classes();

            foreach ($classes as $class) {
                $reflector = new ReflectionClass($class);
                if ($reflector->isInstantiable() && $reflector->getFileName() === realpath($file)) {
                    $controllerName = $reflector->getName();
                    $domainKeyAttribute = $reflector->getAttributes(DomainKeyAttribute::class);

                    if (!isset($domainKeyAttribute)) {
                        continue;
                    }

                    $domainKeyInst = $domainKeyAttribute[0]->newInstance();
                    $controllersEntities[] = new ControllerEntity($reflector->getName(), $domainKeyInst->domainKey);
                }
            }
        }

        return $controllersEntities;
    }

    /**
     * @throws ReflectionException
     */
    private function getControllers(): array
    {
        $controllersEntities = $this->getControllersEntities();
        $controllers = [
            'special' => [],
            'general' => []
        ];

        foreach ($controllersEntities as $controllerEntity) {
            $controllers['special'][$controllerEntity->domainKey][] = $controllerEntity->controller;
            $controllers['general'][] = $controllerEntity->controller;
        }

        return $controllers;
    }


    private function createRegular(string $pattern): string
    {
        $regex = preg_replace('/\{(\w+)}/', '(?P<\1>\d+)', $pattern);
        $regex = str_replace('/', '\/', $regex);

        return sprintf('/^%s$/', $regex);
    }

    private function getUrlDomainKey(string $url): string
    {
        preg_match('#^(/[^/]*)(/[^/]*)?#', $url, $matches);

        return $matches[1];
    }

    /**
     * @throws ReflectionException
     */
    private function getRouteEntityFromControllersArray(array $controllers, string $path, string $method): ?RouteEntity
    {
        foreach ($controllers as $controller) {
            $reflectionClass = new ReflectionClass($controller);
            $actionAndRegular = $this->getActionAndRegular($reflectionClass, $method, $path);

            if ($actionAndRegular !== null) {
                return new RouteEntity($controller, $actionAndRegular->action, $actionAndRegular->urlPattern);
            }
        }

        return null;
    }

    /**
     * @throws ReflectionException
     * @throws StatusErrorException
     */
    private function getRouteEntity(string $path, string $method): RouteEntity
    {
        $firstKey = $this->getUrlDomainKey($path);

        if (isset($this->routesControllers['special'][$firstKey])) {
            $routeEntity = $this->getRouteEntityFromControllersArray(
                $this->routesControllers['special'][$firstKey],
                $path,
                $method
            );

            if ($routeEntity !== null) {
                return $routeEntity;
            }
        }

        $routeEntity = $this->getRouteEntityFromControllersArray(
            $this->routesControllers['general'],
            $path,
            $method
        );

        if ($routeEntity !== null) {
            return $routeEntity;
        }

        throw new StatusErrorException('Url not found...', 404);
    }

    private function getActionAndRegular(
        ReflectionClass $reflectionClass,
        string          $reqMethod,
        string          $path
    ): ?ActionEntity
    {
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(MethodRouteAttribute::class);
            if (empty($attributes)) {
                continue;
            }

            $attribute = $attributes[0]->newInstance();

            $httpMethod = $attribute->getHttpMethod();
            $httpMethodReg = sprintf('/^%s$/', $httpMethod);

            if (!preg_match($httpMethodReg, $reqMethod)) {
                continue;
            }

            $urlPath = $attribute->getUrlPattern();
            $urlPattern = $this->createRegular($urlPath);

            if (!preg_match($urlPattern, $path)) {
                continue;
            }

            return new ActionEntity($method->getName(), $urlPattern);
        }

        return null;
    }

    private function getParams(string $url, string $regex): array
    {
        if (preg_match($regex, $url, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return [];
    }

    /**
     * @throws InvalidRouteArgumentException
     */
    private function validateParams(array $params, array $reflectionParams): array
    {
        $newParams = [];

        foreach ($reflectionParams as $reflectionParam) {
            $name = $reflectionParam->getName();
            if (!isset($params[$name]) && !isset($params[self::REQUEST_INDEX][$name])) {
                if ($reflectionParam->isOptional()) {
                    continue;
                }
                throw new InvalidRouteArgumentException("Missing parameter: $name", 404);
            }

            $type = $reflectionParam->getType();
            if ($type !== null) {
                $typeName = $type->getName();
                $value = $params[$name] ?? $params[self::REQUEST_INDEX][$name];

                if (class_exists($typeName) && method_exists($typeName, 'fromJson')) {
                    $newParams[$name] = $typeName::fromJson($value);
                    continue;
                }

                if ($typeName === 'int') {
                    $newParams[$name] = (int) $value;
                    continue;
                }

                if ($typeName === 'string') {
                    $newParams[$name] = $value;

                }

                throw new InvalidRouteArgumentException("Missing parameter: $name", 404);
            }
        }

        return $newParams;
    }

    /**
     * @throws ReflectionException
     * @throws InvalidRouteArgumentException
     *
     */
    private function run(string $controller, string $action, array $params): void
    {
        $reflectionMethod = new ReflectionMethod($controller, $action);
        $reflectionParams = $reflectionMethod->getParameters();

        $validatedParams = $this->validateParams($params, $reflectionParams);
        unset($validatedParams[self::REQUEST_INDEX]);

        $controllerInstance = new $controller();
        $controllerInstance->$action(...$validatedParams);
    }

    /**
     * @throws ReflectionException
     * @throws StatusErrorException
     * @throws InvalidRouteArgumentException
     */
    public function dispatch(string $path, string $method): void
    {
        $routeEntity = $this->getRouteEntity($path, $method);
        $controller = $routeEntity->controller;
        $action = $routeEntity->action;
        $params = $this->getParams($path, $routeEntity->urlPattern);

        if ($method === 'POST') {
            $params[self::REQUEST_INDEX] = $_POST;
        }

        $this->run(
            $controller,
            $action,
            $params,
        );
    }
}
