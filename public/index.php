<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

use Psr\Log\LoggerInterface;
use App\Logger\Logger;

function get_result_message(mixed $value, string $search_string): string
{
    if ($value === false){
        $message = sprintf('no %s in array was', $search_string);
    }else{
        $message = sprintf('found %s in array at index %s', $search_string, $value);
    }

    return $message;
}

function searching(string $search, array $strings, LoggerInterface $logger): void
{
    $res = array_search($search, $strings, true);
    $message = get_result_message($res, $search);

    $logger->info($message);
}

$logger = new Logger('default');

$data = [
    'mom', 'dad', 'brother', 'sister', 'aunt', 'uncle',
    'red', 'blue', 'white', 'white beard', 'mom cool'
];

searching('mom cool', $data, $logger);


