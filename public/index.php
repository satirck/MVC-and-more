<?php

declare(strict_types=1);

$arr1 = range(1, 10);


$copy = $arr1;

print_r($copy);
echo '<br><br>';

unset($copy);

//error, no such variable
//print_r($copy);

$arr2 = $arr1;

array_splice($arr2, 4, 5);

print_r($arr2);
echo '<br><br>';

//removes 1 elemnt from bottom, offset doesn`t make effects
array_splice($arr2, -3, -1);

print_r($arr2);
echo '<br><br>';
