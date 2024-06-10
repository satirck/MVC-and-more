<?php

declare(strict_types=1);

namespace App\route;

use InvalidArgumentException;
use App\attributes\{MethodRote};
use App\controllers\{HomeController, UserController};

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Route
{
    static function createRegular(string $pattern): string
    {
        $regex = preg_replace('/\{(\w+)}/', '(?P<\1>\d+)', $pattern);
        $regex = str_replace('/', '\/', $regex);

        return sprintf('/^%s$/', $regex);
    }

    /**
     * @throws ReflectionException
     * @throws StatusError
     */
    static function getController(string $path, string $method, array $routesControllers): array
    {
        foreach ($routesControllers as $controller) {
            $reflectionClass = new ReflectionClass($controller);

            $actionAndRegular = self::getActionAndRegular($reflectionClass, $method, $path);
            if ($actionAndRegular !== null) {
                return [
                    $controller, $actionAndRegular[0], $actionAndRegular[1]
                ];
            }
        }

        throw new StatusError(404, 'Url not found...');
    }

    static function getActionAndRegular(ReflectionClass $reflectionClass, string $reqMethod, string $path): array|null
    {
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(MethodRote::class);
            if (empty($attributes)) {
                continue;
            }

            $attribute = $attributes[0]->newInstance();

            $httpMethod = $attribute->getHttpMethod();
            $httpMethodReg = sprintf('/^%s$/', $httpMethod);

            $urlPath = $attribute->getUrlPattern();
            $urlPattern = self::createRegular($urlPath);

            if (!preg_match($httpMethodReg, $reqMethod)) {
                continue;
            }

            if (!preg_match($urlPattern, $path)) {
                continue;
            }

            return [$method->getName(), $urlPattern];
        }

        return null;
    }

    static function getParams(string $url, string $regex): array
    {
        if (preg_match($regex, $url, $matches)) {
            return array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
        }

        return [];
    }

    static function validateParams(array $params, array $reflectionParams): array
    {
        foreach ($reflectionParams as $reflectionParam) {
            $name = $reflectionParam->getName();
            if (!isset($params[$name])) {
                if ($reflectionParam->isOptional()) {
                    continue;
                }
                throw new InvalidArgumentException("Missing parameter: $name");
            }

            $type = $reflectionParam->getType();
            if ($type !== null) {
                $typeName = $type->getName();
                $value = $params[$name];

                if ($typeName === 'int' && !is_int($value)) {
                    if (is_numeric($value)) {
                        $params[$name] = (int)$value;
                    } else {
                        throw new InvalidArgumentException("Invalid type for parameter: $name. Expected int.");
                    }
                } elseif ($typeName === 'string' && !is_string($value)) {
                    throw new InvalidArgumentException("Invalid type for parameter: $name. Expected string.");
                }
            }
        }

        return $params;
    }

    /**
     * @throws ReflectionException
     */
    static function run(string $controller, string $action, array $params, string $method): void
    {
        $controllerInstance = new $controller();
        $reflectionMethod = new ReflectionMethod($controller, $action);
        $reflectionParams = $reflectionMethod->getParameters();

        try {
            $validatedParams = self::validateParams($params, $reflectionParams);
        } catch (InvalidArgumentException $e) {
            die($e->getMessage());
        }

        $controllerInstance->$action(...$validatedParams);
    }

    /**
     * @throws ReflectionException
     * @throws StatusError
     */
    static function dispatch(string $path, string $method): void
    {
        $routesControllers = [
            HomeController::class,
            UserController::class,
        ];

        $ctrlAndAction = self::getController($path, $method, $routesControllers);
        $controller = $ctrlAndAction[0];
        $action = $ctrlAndAction[1];
        $params = self::getParams($path, $ctrlAndAction[2]);

        self::run(
            $controller,
            $action,
            $params,
            $method
        );
    }
}
