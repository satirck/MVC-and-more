<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use App\route\{Route, StatusError};

$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

try {
    Route::dispatch($url, $method);
} catch (StatusError $e) {
    echo sprintf('Error with code [%d]! %s<br>', $e->getCode(), $e->getMessage());
} catch (ReflectionException $e) {
    echo sprintf('Error with controllers reflections in url! %s<br>', $url);
}catch (Exception $e) {
    echo sprintf('Error with controllers exception! %s<br>', $e->getMessage());
}
