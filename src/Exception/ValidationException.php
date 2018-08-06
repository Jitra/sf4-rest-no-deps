<?php
declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class ValidationException extends \InvalidArgumentException
{
    protected $errors = [];
    protected $fieldName;
    protected $message = "Value %value% is invalid";
    protected $statusCode = Response::HTTP_BAD_REQUEST;

    public function __construct(string $fieldName, string $value)
    {
        $this->fieldName = strtolower($fieldName);


        $this->errors[$this->fieldName][] = [
            'params' => [
                '%value%' => $value,
            ],
            'message' => $this->message,
        ];

        parent::__construct($this->message);
    }

    public function getErrors(): array
    {
        $errors = [];
        foreach ($this->errors as $fieldName => $fieldErrors) {
            foreach ($fieldErrors as $key => $error) {
                $errors[$key] = str_replace(
                    array_keys($error['params']),
                    array_values($error['params']),
                    $error['message']
                );
            }
        };

        return $errors;
    }

    public function getTranslatedErrors(TranslatorInterface $translator): array
    {
        $errors = [];

        foreach ($this->errors as $fieldName => $fieldErrors) {
            foreach ($fieldErrors as $key => $error) {
                $errors[$fieldName][$key] = $translator->trans($error['message'], $error['params']);
            }
        }

        return $errors;

    }

    public function setErrors(array $errors)
    {
        $this->errors[$this->fieldName] = $errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}