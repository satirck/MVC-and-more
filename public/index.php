<?php

declare(strict_types=1);

$message = 'This day is really cool cause of some events';

$encodeBaseMessage = base64_encode($message);
$decodeBaseMessage = base64_decode($encodeBaseMessage);

echo sprintf('Base64: <br> This message was first [%s]. Encode: [%s]; Decode: [%s]<br>',
    $message, $encodeBaseMessage, $decodeBaseMessage);

$newMessage = 'Second one';
$encodeBaseNewMessage = base64_encode($newMessage);
$decodeBaseNewMessage = base64_decode($encodeBaseNewMessage);

echo sprintf('Base64 for another one: <br> This message was first [%s]. Encode: [%s]; Decode: [%s]<br>',
    $newMessage, $encodeBaseNewMessage, $decodeBaseNewMessage);

$isEquals = strcmp($encodeBaseMessage, $encodeBaseNewMessage);

echo sprintf('Are [%s] == [%s] ? %s', $encodeBaseMessage, $encodeBaseNewMessage, $isEquals === 0 ? 'true' : 'false<br>');