<?php

declare(strict_types=1);

require_once 'autoloader.php';

use App\Data\User;

function generate_array(): array
{
    $data = array();

    for ($i = 0; $i < 40; $i++) {
        $age = random_int(1, 30);

        $name = sprintf('%d ne %d xd', $age, $age);
        $data[] = new User($name, $age);
    }

    return $data;
}

function is_elder(User $user): bool
{
    return !($user->getAge() < 18);
}

function filter_array_by_age(array $arr): array
{
    return array_filter($arr, 'is_elder');
}

function print_array(array $arr): void
{
    foreach ($arr as $item) {
        echo $item;
    }
}

$data = generate_array();

echo 'non filtered<br>';
print_r($data);
echo '<br>';

$filtered = filter_array_by_age($data);

echo 'filtered<br>';
print_array($filtered);
