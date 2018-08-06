<?php
declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable
 */
class Hash implements JsonSerializable
{
    /**
     * @ORM\Column(name = "hash", type = "string", nullable=true)
     * @var string
     */
    private $hash;

    /**
     * Bearer constructor.
     */
    public function __construct()
    {
        $this->hash = md5(random_bytes(10));
    }

    public static function simpleHash()
    {
        $hash = new self();
        $hash->hash = rand(1000000, 9999999);
        return $hash;
    }

    public static function existing(string $hash)
    {
        $self = new self;
        $self->hash = $hash;

        return $self;
    }

    /**
     * @param Hash $hash
     * @return bool
     */
    public function isEqual(Hash $hash): bool
    {
        return $this->hash === $hash->hash;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->hash;
    }

    public function jsonSerialize()
    {
        return (string)$this;
    }
}