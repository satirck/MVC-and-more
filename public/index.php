<?php

declare(strict_types=1);

$message = '1Hello world1';

$res1 = trim($message, '1');
echo $res1;

$res2 = str_replace('world', 'Vlad', $res1);
echo '<br>';
echo $res2;

$pattern = '/\d/';
$replace = '#replaced#';

$res3 = preg_replace($pattern, $replace, $message);
echo '<br>';
echo $res3;