<?php
declare(strict_types = 1);

namespace App\Form;

use App\Entity\ValueObject\DeviceInfo;
use App\Entity\ValueObject\EmailAddress;
use App\Entity\ValueObject\Locale;

class LoginData
{
    /**
     * @var EmailAddress
     */
    public $login;

    /**
     * @var string
     */
    public $password;

    /**
     * @var DeviceInfo
     */
    public $deviceInfo;

    /**
     * @var Locale
     */
    public $locale;


}