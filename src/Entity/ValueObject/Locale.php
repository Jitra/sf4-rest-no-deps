<?php
declare(strict_types=1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;


/**
 * @ORM\Embeddable
 */
class Locale implements JsonSerializable
{
    const LOCALE_DEFAULT = 'da';
    const LOCALE_ENGLISH = 'en';
    const LOCALE_POLISH = 'pl';

    /**
     * @ORM\Column(name = "locale", type = "string")
     * @var string
     */
    private $locale;

    /**
     * URL constructor.
     * @param string $locale
     */
    private function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    public static function default()
    {
        return new self(self::LOCALE_DEFAULT);
    }

    public static function english(): self
    {
        return new self(self::LOCALE_ENGLISH);
    }

    public static function danish(): self
    {
        return new self(self::LOCALE_POLISH);
    }

    public static function fromStringOrDefault(string $locale): self
    {

        switch ($locale) {
            case self::LOCALE_ENGLISH:
                return self::english();
            case self::LOCALE_POLISH:
                return self::danish();
        }
        return self::default();
    }

    function __toString(): string
    {
        return (string)$this->locale;
    }

    public function jsonSerialize()
    {
        return (string)$this;
    }
}