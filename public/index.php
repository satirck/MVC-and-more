<?php

declare(strict_types=1);

require_once 'autoloader.php';

$str1 = 'Cool day';
$str2 = 'Cool day';

$str3 = 'cool day';

$res1 = strcasecmp($str1, $str2);

echo sprintf('Case independent: <br>Strings [%s] and [%s] are: %s<br>', $str1, $str2, $res1 == 0 ? 'equals' : 'not equals');

$res2 = strcasecmp($str2, $str3);

echo sprintf('Strings [%s] and [%s] are: %s<br>', $str2, $str3, $res2 == 0 ? 'equals' : 'not equals');

$res3 = strnatcmp($str2, $str3);

echo sprintf('Case dependent: <br>Strings [%s] and [%s] are: %s<br>', $str2, $str3, $res3 == 0 ? 'equals' : 'not equals');

function sortStrings(array &$strings, bool $ascending = true): void {
    // Сравнительная функция для сортировки с учетом регистра
    $cmpFunc = function ($a, $b) use ($ascending) {
        return $ascending ? strnatcmp($a, $b) : strnatcmp($b, $a);
    };

    // Сортировка массива
    usort($strings, $cmpFunc);
}


$strings = ["apple", "Banana", "grape", "cherry", "Apple", "banana"];

sortStrings($strings);

echo 'Сортировка по возрастанию:<br>';
print_r($strings);
echo '<br>';


sortStrings($strings, false);

echo 'Сортировка по убыванию:<br>';
print_r($strings);
echo '<br>';