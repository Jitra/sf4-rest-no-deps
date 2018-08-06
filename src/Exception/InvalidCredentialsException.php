<?php
declare(strict_types = 1);

namespace App\Exception;

class InvalidCredentialsException extends UncategorizedValidationException
{
    public function __construct()
    {
        parent::__construct("Invalid login or password");
    }
}