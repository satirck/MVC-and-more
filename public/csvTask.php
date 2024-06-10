<?php

function loadUsersFromFile(string $filePath): array
{
    if (!file_exists($filePath)) {
        die("CSV file '$filePath' does not exist.");
    }

    $file = fopen($filePath, 'r');
    $headers = fgetcsv($file);
    $data = array();

    while (($row = fgetcsv($file)) !== false) {
        $data[] = array_combine($headers, $row);
    }

    fclose($file);

    return $data;
}

function filterUsersByDomain(array $users, string $domain): array {
    return array_filter($users, function($user) use ($domain) {
        $mailPattern = '/(^\S+)@(\S+)\.(\S+$)/';
        if (preg_match($mailPattern, $user['Email'], $matches)) {
            return $matches[3] === $domain;
        }
        return false;
    });
}

function printUsers(array $users): void
{
    foreach ($users as $user) {
        echo sprintf('[%s, Email: %s]<br>', $user['Name'], $user['Email']);
    }
}

$filename = 'users.csv';
$mailPattern = '/(^\S+)@(\S+)\.(\S+$)/';

$users = loadUsersFromFile($filename);

echo 'All users: <br>';
printUsers($users);

echo 'Filtered by mail domain(ru): <br>';

$newUsers = filterUsersByDomain($users, 'ru');
printUsers($newUsers);
