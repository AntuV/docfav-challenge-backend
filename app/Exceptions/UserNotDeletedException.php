<?php

namespace App\Exceptions;

use Exception;

class UserNotDeletedException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct('The user could not be deleted: ' . $message);
    }
}
