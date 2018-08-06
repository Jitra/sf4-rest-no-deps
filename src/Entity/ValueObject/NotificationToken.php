<?php
declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Embeddable
 */
class NotificationToken implements JsonSerializable
{
    /**
     * @ORM\Column(name = "token", type = "string", nullable=true)
     * @var string
     */
    private $token;


    public function __construct(string $token)
    {
        if(strlen($token) < 1){
            throw new \InvalidArgumentException('Notification token cannot be an empty string');
        }
        $this->token = $token;

    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->token;
    }


    public function jsonSerialize()
    {
       return (string)$this;
    }
}