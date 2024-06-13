<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use App\Route\RouteMapper;
use App\Route\Exceptions\{StatusErrorException, InvalidRouteArgumentException};

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

$whoops = new Run;
$whoops->pushHandler(new PrettyPageHandler);
$whoops->register();

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$routeMapper = new RouteMapper('app/Route/Controllers');

try {
    $routeMapper->dispatch($url, $method);
} catch (StatusErrorException | InvalidRouteArgumentException $e) {
    http_response_code($e->getCode());
    echo sprintf('Error with code [%d]! %s<br>', $e->getCode(), $e->getMessage());
}
catch (ReflectionException $e) {
    http_response_code($e->getCode());
    echo sprintf('Error with controllers reflections in url! %s<br>', $url);
}
