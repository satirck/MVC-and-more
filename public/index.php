<?php

declare(strict_types=1);

$numerics = [
    'grades' => range(1, 20),
    'scores' => range(20, 1),
];

foreach ($numerics as $grades) {
    print_r(array_map(fn($value): int => $value * $value, $grades));
    echo '<br><br>';
}
