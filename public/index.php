<?php

declare(strict_types=1);

function areAnagrams(string $word1, string $word2): bool {
    $array1 = str_split($word1);
    $array2 = str_split($word2);
    
    sort($array1);
    sort($array2);
    
    return $array1 === $array2;
}

function isPalindrome(string $text): bool {
    $text = strtolower(str_replace(' ', '', $text));
    
    $array = str_split($text);
    
    $reversedArray = array_reverse($array);

    return $array === $reversedArray;
}

$word1 = 'listen';
$word2 = 'silent';
$word3 = 'apple';

$text1 = "Hello world";
$text2 = "Madam";

echo 'Anagrams:<br>';
echo sprintf('Words [%s] and [%s] are %s<br>', $word1, $word2,
    areAnagrams($word1, $word2) ? 'anagrams' : 'not anagrams');
echo sprintf('Words [%s] and [%s] are %s<br>', $word2, $word3,
    areAnagrams($word2, $word3) ? 'anagrams' : 'not anagrams');

echo 'Palindromes:<br>';
echo sprintf('Word [%s] is %s<br>', $text1,
    isPalindrome($text1) ? 'Palindrome' : 'not a Palindrome');
echo sprintf('Word [%s] is %s<br>', $text2,
    isPalindrome($text2) ? 'Palindrome' : 'not a Palindrome');
