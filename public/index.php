<?php

declare(strict_types=1);

function analyzeText($text) : void {
    $wordCount = str_word_count($text);
    $splitArr = explode(' ', $text);
    $wordArr = str_word_count($text, 2);

    echo sprintf('Text is <br>%s<br>', $text);
    echo sprintf('Word count is %s<br>', $wordCount);
    echo sprintf('Word count by space separator %s<br>', count($splitArr));
    print_r($wordArr);
    echo '<br><br>';
}

function removeDuplicates(string $text): string {
    $words = explode(' ', $text);

    $uniqueWords = array_unique($words);

    return implode(' ', $uniqueWords);
}

$text = 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi, placeat ullam aspernatur 
architecto et, earum maxime vitae quam reiciendis sint ad velit delectus porro nulla aliquid non
facilis perspiciatis autem. ';

$text2 = 'My cool Dad. My cool Mum.';

analyzeText($text2);

$res1 = removeDuplicates($text2);
echo 'Unique: <br>';

analyzeText($res1);

