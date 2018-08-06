<?php
declare(strict_types = 1);

namespace App\Exception;

class NotUniqueException extends ValidationException
{
    protected $message = '%value% is not unique';
}