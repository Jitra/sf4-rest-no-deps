<?php
declare(strict_types=1);

namespace App\CommandBus;

use App\Entity\ValueObject\DeviceInfo;
use App\Entity\ValueObject\Bearer;
use App\Entity\ValueObject\EmailAddress;
use App\Entity\ValueObject\Locale;
use App\Service\CommandBus\CommandInterface;

class UserLoginCommand implements CommandInterface
{
    /**
     * @var EmailAddress
     */
    protected $email;
    /**
     * @var string
     */
    protected $password;

    /**
     * @var Bearer
     */
    protected $bearer;
    /**
     * @var DeviceInfo
     */
    protected $deviceInfo;
    /**
     * @var Locale
     */
    protected $locale;


    public function __construct(EmailAddress $email, string $password, Bearer $bearer, DeviceInfo $deviceInfoData)
    {
        $this->email = $email;
        $this->password = $password;
        $this->bearer = $bearer;
        $this->deviceInfo = $deviceInfoData;
        $this->locale = Locale::default();
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getBearer(): Bearer
    {
        return $this->bearer;
    }

    public function getDeviceInfo(): DeviceInfo
    {
        return $this->deviceInfo;
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }

    /**
     * @param Locale $locale
     */
    public function setLocale(Locale $locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @param DeviceInfo $deviceInfo
     */
    public function setDeviceInfo(DeviceInfo $deviceInfo): void
    {
        $this->deviceInfo = $deviceInfo;
    }
}