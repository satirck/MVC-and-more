<?php

declare(strict_types=1);

$message = '1Hello world1';
$find = 'Hello';

$res1 = strpos($message, $find, 0);
echo $res1 === false ? 'not found' : ' found';
echo '<br>';

$res2 = strpos($message, $find, 6);
echo $res2 === false ? 'not found' : ' found';
