<?php

declare(strict_types=1);

namespace App\route;

use Exception;

class StatusError extends Exception
{
    public function __construct(int $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;

        parent::__construct($message, $code);
    }
}