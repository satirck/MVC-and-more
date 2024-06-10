<?php

declare(strict_types=1);

namespace App\Logger;

use Exception;

class FileNotFoundException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }
}
