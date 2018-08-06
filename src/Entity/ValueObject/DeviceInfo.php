<?php
declare(strict_types = 1);

namespace App\Entity\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use App\Exception\NotImplementedException;
use JsonSerializable;

/** @noinspection PhpUndefinedClassInspection */

/**
 * @ORM\Embeddable
 */
class DeviceInfo implements JsonSerializable
{
    const TYPE_ANGULAR = 'angular';
    const TYPE_IOS = 'ios';
    const TYPE_ANDROID = 'android';
    const TYPE_THIRD_PARTY = 'third-party';

    /**
     * @ORM\Column(name="type", type = "string")
     * @var string
     */
    private $type;

    /**
     * @ORM\Column(name="device_id", type="string", nullable=true)
     * @var null|string
     */
    private $deviceId;

    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\NotificationToken")
     * @var null|NotificationToken
     */
    private $pushNotificationToken;

    private function __construct(string $type)
    {
        $this->type = $type;
    }

    public static function angular(): self
    {
        return new self(self::TYPE_ANGULAR);
    }

    public static function iOS(): self
    {
        return new self(self::TYPE_IOS);
    }

    public static function android(): self
    {
        return new self(self::TYPE_ANDROID);
    }

    public static function thirdParty(): self
    {
        return new self(self::TYPE_THIRD_PARTY);
    }

    public static function autoDetect(string $type)
    {
        if(in_array($type, [self::TYPE_ANDROID, self::TYPE_IOS, self::TYPE_ANGULAR])){
            return new self($type);
        }else{
            return self::thirdParty();
        }
    }

    function __toString(): string
    {
        return (string)$this->type;
    }

    public function getDeviceId(): string
    {
        return (string)$this->deviceId;
    }

    public function setDeviceId(string $deviceId): void
    {
        $this->deviceId = $deviceId;
    }

    public function getPushNotificationToken(): ?NotificationToken
    {
        return (string)$this->pushNotificationToken ? $this->pushNotificationToken : null;
    }

    public function setPushNotificationToken(?NotificationToken $token): void
    {
        $this->pushNotificationToken = $token;
    }

    public function isMobile(): bool {
        $mobileDeviceTypes = [self::TYPE_IOS, self::TYPE_ANDROID];

        return in_array($this->getType(), $mobileDeviceTypes);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    public function jsonSerialize()
    {
        return [
            'type' => $this->type,
            'deviceId' => $this->deviceId,
            'pushNotificationToken' => $this->pushNotificationToken
        ];
    }
}
