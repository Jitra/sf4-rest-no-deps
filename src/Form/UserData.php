<?php
declare(strict_types = 1);

namespace App\Form;


use App\Entity\ValueObject\EmailAddress;
use App\Entity\ValueObject\PasswordHash;
use App\Entity\ValueObject\PhoneNumber;

class UserData
{
    /**
     * @var EmailAddress
     */
    public $email;
    /**
     * @var PasswordHash
     */
    public $password;
    /**
     * @var string
     */
    public $firstName;
    /**
     * @var string
     */
    public $lastName;
    /**
     * @var null|PhoneNumber
     */
    public $phoneNumber;
}