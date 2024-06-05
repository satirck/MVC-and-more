<?php

declare(strict_types=1);

function isPasswordStrong($password): bool
{
    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[\W_]).{8,}$/';
    return !(preg_match($pattern, $password) === false);
}

function isValidIPAddress($ip): bool
{
    $pattern = '/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/';
    preg_match($pattern, $ip, $matches);

    if (count($matches) == 5) {
        $parts = array_slice($matches, 1);

        $filteredParts = array_filter($parts, function ($part) {
            return $part >= 0 && $part <= 255;
        });

        return count($filteredParts) == 4;
    }
    return false;
}

$phone = "79123456789";

$pattern = '/(\d)(\d{3})(\d{3})(\d{4})/';
preg_match($pattern, $phone, $matches);

$resPhone = sprintf('+%s (%s) %s-%s', $matches[1], $matches[2], $matches[3], $matches[4]);
echo sprintf('Orig: [%s] now: [%s]<br>', $phone, $resPhone);

$password = "YourP@ssw0rd";
echo sprintf('Your password [%s] is %ssecure<br>', $password, isPasswordStrong($password) ? '' : 'not ');

$ip = "192.300.1.1";
echo sprintf('Your ip [%s] is %scorrect<br>', $ip, isValidIPAddress($ip) ? '' : 'not ');

$text = "Этот текс содержит ошибки, таке как опечатка в слове текс или неверное написание слова таке.";

$newText = preg_replace('/текс/', 'текст', $text);
$newText = preg_replace('/тaке/', 'такие', $newText);

echo $newText;
