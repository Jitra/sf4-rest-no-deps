<?php
declare(strict_types = 1);

namespace App\Exception;

class UncategorizedValidationException extends ValidationException
{
    protected $message = null;

    public function __construct($error)
    {
        $this->message = $error;
        parent::__construct("uncategorized", '');
    }
}