<?php

declare(strict_types=1);

function isValidEmail(string $email): bool
{
    $pattern = '/(^\S+)@(\S+)\.(\S+$)/';

    return preg_match($pattern, $email) === 1;
}

function parseFromCapital($string): array
{
    $pattern = '/([A-Z].+)/m';

    preg_match_all($pattern, $string, $matches);
    return $matches[0];
}

function extractUrls(string $html): array
{
    $pattern = '/https?:\/\/[^\s"<>\']+/i';

    preg_match_all($pattern, $html, $matches);

    return $matches[0];
}

function extractBetweenSymbols(string $text): array
{
    $pattern = '/(?<=,)[^,]+(?=,)/';

    preg_match_all($pattern, $text, $matches);

    return $matches[0];
}

function replaceToString(string $first, string $toSearch, string $toReplace): string
{
    return preg_replace($toSearch, $toReplace, $first);
}

$html_string = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Hello world
    <a href="http://localhost"></a>
    <a href="https://vk.com"></a>
</body>
</html>';

$email1 = 'retdarkw@gmail.com';
$email2 = 'retdarkw.gmail@.com';

$string = sprintf('Cool day%sCall me%scoach%sletter', PHP_EOL, PHP_EOL, PHP_EOL);
$string2 = 'Hello, me friend, hello, cool day. Can we speak together, a, cool.';
$string3 = 'Говоря привет, мы вежливо сообщаем что рады видеть человека. 
Даже такое обращение как привет, может вызвать улыбку.';

echo 'Mails: <br>';

echo sprintf('Mail [%s] is %s mail<br>', $email1, isValidEmail($email1) ? 'valid' : 'invalid');
echo sprintf('Mail [%s] is %s mail<br><br>', $email2, isValidEmail($email2) ? 'valid' : 'invalid');

echo sprintf('Getting capitals from <br>%s', str_replace(PHP_EOL, '<br>', $string));

$capitals = parseFromCapital($string);
print_r($capitals);
echo '<br>';

$escaped_html = htmlspecialchars($html_string, ENT_QUOTES, 'UTF-8');
$urls = extractUrls($html_string);

echo sprintf('Original: %s<br><br>', $escaped_html);
echo 'Extracted urls: <br>';
print_r($urls);
echo '<br><br>';

$quBetween = extractBetweenSymbols($string2, ',');

echo sprintf('Original: %s<br>', $string2);
echo 'Extracted between [,] : <br>';
print_r($quBetween);
echo '<br>';

$newString3 = replaceToString($string3, '/привет/', 'здравствуй');
echo sprintf('Original: %s<br>', $string3);
echo sprintf('Replace [привет] на [здравствуй] : <br> %s<br>', $newString3);
