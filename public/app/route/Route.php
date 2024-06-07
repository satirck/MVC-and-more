<?php

declare(strict_types=1);

namespace App\route;

use App\Attributes\ControllerRote;
use App\Attributes\MethodRote;
use App\controllers\{HomeController, UserController};
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Route
{

    /**
     * @throws ReflectionException
     * @throws StatusError
     */
    static function getController(string $path, array $routesControllers): string
    {
        foreach ($routesControllers as $controller) {
            $reflectionClass = new ReflectionClass($controller);

            $attribute = $reflectionClass->getAttributes(ControllerRote::class)[0]->newInstance();

            $urlPattern = $attribute->getRegPattern();

            if (!preg_match($urlPattern, $path)) {
                continue;
            }

            return $controller;
        }

        throw new StatusError(404, 'Url pattern not found');
    }

    /**
     * @throws ReflectionException
     * @throws StatusError
     */
    static function getAction(string $controller, string $reqMethod, string $path): string
    {
        $reflectionClass = new ReflectionClass($controller);
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($methods as $method) {
            $attributes = $method->getAttributes(MethodRote::class);
            if (empty($attributes)) {
                continue;
            }

            $attribute = $attributes[0]->newInstance();

            $httpMethod = $attribute->getHttpMethod();
            $httpMethodReg = sprintf('/^%s$/', $httpMethod);
            $urlPattern = $attribute->getUrlPattern();

            if (!preg_match($httpMethodReg, $httpMethod)) {
                continue;
            }

            if (!preg_match($urlPattern, $path)) {
                continue;
            }

            return $method->getName();
        }

        throw new StatusError(404, 'Url method not found');
    }

    static function getParams(string $action, string $controller): array
    {
        return array();
    }

    static function run(string $controller, string $action, array $params): void
    {
        $controller = new $controller();
        $controller->$action();

        //$controller->$action($params);
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

        $controller = self::getController($path, $routesControllers);
        $action = self::getAction($controller, $method, $path);
        $params = self::getParams($action, $controller);

        self::run(
            $controller,
            $action,
            []
        );
    }
}

