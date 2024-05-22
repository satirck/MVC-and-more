<?php

declare(strict_types=1);

$arr1 = [
    'must' => 'test',
    'important' => 'family',
    'php' => 'interesting',
    'dump' => 'test'
];

print_r($arr1);
echo '<br><br>';

$keys = array_keys($arr1);

print_r($keys);
echo '<br><br>';

$keys = array_keys($arr1, 'test',true);

print_r($keys);
echo '<br><br>';

$values = array_values($arr1);

print_r($values);
echo '<br><br>';

foreach ($keys as $key){
    $arr1[$key] = 'new value';
}

echo 'After changing<br>';
print_r($arr1);
echo '<br><br>';
