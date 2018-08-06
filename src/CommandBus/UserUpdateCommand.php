<?php
declare(strict_types = 1);

namespace App\CommandBus;


use App\Entity\ValueObject\EmailAddress;
use App\Entity\ValueObject\PhoneNumber;
use App\Entity\ValueObject\Uuid;
use App\Service\CommandBus\CommandInterface;

class UserUpdateCommand implements CommandInterface
{
    /**
     * @var Uuid
     */
    protected $id;
    /**
     * @var EmailAddress
     */
    protected $emailAddress;
    /**
     * @var string
     */
    protected $firstName;
    /**
     * @var string
     */
    protected $lastName;
    /**
     * @var null|PhoneNumber
     */
    protected $phoneNumber;

    public function __construct(Uuid $id, EmailAddress $emailAddress)
    {
        $this->id = $id;
        $this->emailAddress = $emailAddress;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return EmailAddress
     */
    public function getEmailAddress(): EmailAddress
    {
        return $this->emailAddress;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return PhoneNumber|null
     */
    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @param PhoneNumber|null $phoneNumber
     */
    public function setPhoneNumber(?PhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }
}