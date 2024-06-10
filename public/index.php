<?php

declare(strict_types=1);

$strings = [
    'school', 'family', 'daily', 'dairy', 'poem', 'poet'
];

$copy = $strings;

print_r($strings);
echo '<br><br>';

print_r(array_map('strtoupper', $copy));
echo '<br><br>';
