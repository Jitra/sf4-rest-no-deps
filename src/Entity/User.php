<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\PasswordHash;
use App\Entity\ValueObject\PhoneNumber;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\ValueObject\Uuid;
use App\Entity\ValueObject\EmailAddress;

/**
 * @ORM\Entity
 * @ORM\Table(uniqueConstraints={
 *     @ORM\UniqueConstraint(name="unique_email", columns={"email_email"})}
 * )
 */
class User extends BaseUser
{
    protected const ROLE_USER = 'ROLE_USER';

    /**
     * @ORM\Embedded(class = "App\Entity\ValueObject\EmailAddress")
     * @var EmailAddress
     */
    protected $email;

    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $firstName;
    /**
     * @ORM\Column(type="string", nullable=false)
     * @var string
     */
    protected $lastName;

    /**
     * @ORM\Embedded(class = "App\Entity\ValueObject\PhoneNumber")
     * @var null|PhoneNumber
     */
    protected $phoneNumber;


    public function __construct(Uuid $id, EmailAddress $email, PasswordHash $password)
    {

        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
    }

    public function getEmail(): EmailAddress
    {
        return $this->email;
    }

    public function setEmail(EmailAddress $email): void
    {
        $this->email = $email;
    }

    public function getUsername(): EmailAddress
    {
        return $this->email;
    }

    public function getFirstName(): string
    {
        return (string)$this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }


    public function getLastName(): string
    {
        return (string)$this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?PhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getRoles(): array
    {
        return [self::ROLE_USER];
    }
}
