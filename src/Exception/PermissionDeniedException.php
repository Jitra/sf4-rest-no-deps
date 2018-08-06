<?php
declare(strict_types = 1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\HttpException;

class PermissionDeniedException extends HttpException
{
    public function __construct(string $message = "")
    {
        parent::__construct(403, $message);
    }
}