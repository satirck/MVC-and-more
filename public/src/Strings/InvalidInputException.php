<?php

namespace App\Strings;

class InvalidInputException extends \Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}