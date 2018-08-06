<?php
declare(strict_types=1);

namespace App\Entity\ValueObject;

use InvalidArgumentException;
use JsonSerializable;
use Ramsey\Uuid\Uuid as UuidGenerator;

class Uuid implements JsonSerializable
{
    private $id;

    public function __construct()
    {
        $this->id = strtoupper((string)UuidGenerator::uuid4());
    }

    public static function existing(string $uuid): Uuid
    {
        if (!self::isValid(strtoupper($uuid))) {
            throw new InvalidArgumentException(sprintf("Uuid '%s' is not valid", $uuid));
        }

        $existingUuid = new self;
        $existingUuid->id = strtoupper($uuid);
        return $existingUuid;
    }

    public static function isValid(string $uuid): bool
    {
        return UuidGenerator::isValid(strtoupper($uuid));
    }

    public function __toString(): string
    {
        return (string)$this->id;
    }

    function jsonSerialize(): string
    {
        return (string)$this->id;
    }
}