<?php
declare(strict_types = 1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * Class PhoneNumber
 * @ORM\Embeddable
 */
class PhoneNumber implements JsonSerializable
{
    /**
     * @var string
     * @ORM\Column(name="number", type="string", nullable=true)
     */
    protected $number = '';

    public function __construct(string $number)
    {
        $this->number = $number;

        $this->validate();
    }

    private function validate(): void
    {
        // simplified validation allow +,(number), number{space}number, number-number
        if (!preg_match(
            '/^(\+?(\([0-9]{1,}\))?(\s?|-?)([0-9]\s?)*([0-9]\-?)*)\d$/m',
            $this->number)
        ) {
            throw new \InvalidArgumentException("Invalid phone number format {$this->number}");
        }
    }

    function __toString(): string
    {
        return (string)$this->number;
    }

    public function jsonSerialize()
    {
        return (string)$this;
    }
}