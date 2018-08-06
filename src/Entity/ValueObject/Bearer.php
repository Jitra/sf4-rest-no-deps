<?php
declare(strict_types = 1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/** @noinspection PhpUndefinedClassInspection */

/**
 * @ORM\Embeddable
 */
class Bearer implements JsonSerializable
{
    /**
     * @ORM\Column(name = "token", type = "string", nullable=true)
     * @var string
     */
    protected $token;

    /**
     * Bearer constructor.
     */
    public function __construct()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZhhhh';
        $randstring = '';
        for ($i = 0; $i < 64; $i++) {
            $randstring .= $characters[rand(0, strlen($characters) - 1)];
        }

        $this->token = $randstring;
    }

    public static function existing(string $token): self
    {
        $self = new self;
        $self->token = $token;

        return $self;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string)$this->token;
    }

    public function jsonSerialize()
    {
       return (string)$this;
    }
}