<?php

declare(strict_types=1);

function getEmails($string) {
    $email_pattern = '/[^\s@]+@[^\s@]+\.[^\s@]+/';
    preg_match_all($email_pattern, $string, $matches);
    return $matches[0];
}

$string = "Привет, мой email адрес: example@example.com. Буду рад получить ваше письмо. Мой друг email@example.com также ждет ваших новостей. Напишите нам скорее!";
$emails = getEmails($string);

echo "Найденные email адреса:\n";
print_r($emails);
