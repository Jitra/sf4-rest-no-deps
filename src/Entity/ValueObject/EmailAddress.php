<?php
declare(strict_types = 1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/** @noinspection PhpUndefinedClassInspection */


/**
 * @ORM\Embeddable
 */
class EmailAddress implements JsonSerializable
{
    /**
     * @ORM\Column(name = "email", type = "string", nullable=true)
     * @var string
     */
    private $email = '';

    /**
     * EmailAddress constructor.
     * @param string $email
     */
    public function __construct(string $email)
    {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException("$email is not valid email");
        }
        $this->email = $email;
    }

    public function __toString() : string
    {
        return (string)$this->email;
    }

    public function equals(EmailAddress $emailAddress): bool
    {
        return $emailAddress->email == $this->email;
    }

    function jsonSerialize(): string
    {
        return (string)$this->email;
    }
}