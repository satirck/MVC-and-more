<?php

declare(strict_types=1);

//block of trim
$message = '1Hello world1';

//exp 'Hello world'
$res1 = trim($message, '1');


//block of str_replace
//exp 'Hello Vlad'
$res2 = str_replace('world', 'Vlad', $res1);
echo $res2;
