<?php

declare(strict_types=1);

require_once 'vendor/autoload.php';

function generate_array(): array
{
    $data = array();

    for ($i = 0; $i < 40; $i++) {
        $number = random_int(1, 30);
        $data[] = $number;
    }

    return $data;
}

$logger = new App\Logger\Logger('default');

$nums = generate_array();

$logger->info('Original array');
var_dump($nums);
echo '<br>';

sort($nums);

$logger->info('Sorted array');
var_dump($nums);
echo '<br>';

rsort($nums);

$logger->info('Reverse sorted array');
var_dump($nums);
echo '<br>';


