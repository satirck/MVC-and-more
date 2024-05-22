<?php

declare(strict_types=1);

$arr1 = [
    'parents' => array('mom', 'dad'),
    'key' => array(array('value 1', 'value 2'), 1),
    'value'
];

$arr2 = [
    range(1, 12),
    'parents' => array('mom 1', 'dad 2'),
];

$res = array_merge_recursive($arr1, $arr2);

print_r($res);
echo '<br><br>';

$res = array_merge($arr1, $arr2);
print_r($res);
echo '<br><br>';
