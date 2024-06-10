<?php

declare(strict_types=1);

function removeHtmlTags(string $html): string {
    return preg_replace('/<[.]*>/i', '', $html);
}

function extractUrls(string $html): array {
    $pattern = '/https?:\/\/[^\s"<>\']+/i';

    preg_match_all($pattern, $html, $matches);

    return $matches[0];
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

$escaped_html = htmlspecialchars($html_string, ENT_QUOTES, 'UTF-8');
$clean_string = removeHtmlTags($html_string);

echo sprintf('Original: %s<br><br>', $escaped_html);
echo sprintf('Clean: %s<br><br>', $clean_string);

$urls = extractUrls($html_string);

echo 'Extracted urls: <br>';
print_r($urls);
echo '<br>';
