<?php
declare(strict_types = 1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable
 */
class SaltHash implements JsonSerializable
{
    /**
     * @ORM\Column(name = "hash", type = "string")
     * @var string
     */
    protected $hash;

    /**
     * Bearer constructor.
     */
    public function __construct()
    {
        $this->hash = md5(random_bytes(10));
    }

    public static function existing(string $hash) : SaltHash
    {
        $self = new self();
        $self->hash = $hash;
        return $self;
    }

    /**
     * @param SaltHash $hash
     * @return bool
     */
    public function isEqual(SaltHash $hash) : bool
    {
        return $this->hash === $hash->hash;
    }

    /**
     * @return string
     */
    public function __toString() : string
    {
        return (string)$this->hash;
    }

    public function jsonSerialize()
    {
       return (string)$this;
    }
}