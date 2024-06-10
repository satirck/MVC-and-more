<?php

declare(strict_types=1);

function array_copy(array $arr, int $start, int $stop): array
{
    return array_slice($arr, $start, $stop - $start);
}

function merge(array $left, array $right): array
{
    $sorted = array();
    $i = $j = 0;
    while ($i < count($left) && $j < count($right)) {
        if ($left[$i] < $right[$j]) {
            $sorted[] = $left[$i];
            $i++;
        } else {
            $sorted[] = $right[$j];
            $j++;
        }
    }

    while ($i < count($left)) {
        $sorted[] = $left[$i];
        $i++;
    }

    while ($j < count($right)) {
        $sorted[] = $right[$j];
        $j++;
    }

    return $sorted;
}

function merge_sort(array $arr): array
{
    $count = count($arr);

    if ($count < 2) {
        return $arr;
    }

    $mid = (int)($count / 2);

    $left = merge_sort(array_copy($arr, 0, $mid));
    $right = merge_sort(array_copy($arr, $mid, $count));

    return merge($left, $right);
}

$nums = [5, 3, 2, 7, 8, 9, 20];

$sorted_nums = merge_sort($nums);

print_r($sorted_nums);

