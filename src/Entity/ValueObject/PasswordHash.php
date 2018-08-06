<?php
declare(strict_types=1);

namespace App\Entity\ValueObject;

use App\Entity\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @ORM\Embeddable
 */
class PasswordHash implements JsonSerializable
{
    /**
     * @ORM\Column(name = "hash", type = "string")
     * @var string
     */
    private $hash;

    /**
     * @ORM\Embedded(class = "App\Entity\ValueObject\SaltHash")
     * @var SaltHash
     */
    private $salt;

    /**
     * Bearer constructor.
     * @param string $hash
     * @param SaltHash $salt
     */
    private function __construct(string $hash, SaltHash $salt)
    {
        $this->hash = $hash;
        $this->salt = $salt;
    }

    public static function createUsingPasswordEncoder(UserPasswordEncoderInterface $passwordEncoder, string $newPassword, SaltHash $salt): self
    {
        // Hack poor encoder. UserInterface required... c'mon d(-.-)b
        $userMock = new class implements UserInterface
        {
            protected $password = '';
            protected $salt = '';

            public function setDefaults(string $password, SaltHash $salt)
            {
                $this->password = $password;
                $this->salt = $salt;
            }

            public function getPassword(): string { return $this->password; }
            public function getSalt() { return $this->salt; }
            public function getUsername() { return ''; }
            public function getRoles() { return; }
            public function eraseCredentials() { return; }
        };

        $userMock->setDefaults($newPassword, $salt);

        return new self($password = $passwordEncoder->encodePassword($userMock, $newPassword), $salt);
    }

    /**
     * @return SaltHash
     */
    public function getSalt(): SaltHash
    {
        return $this->salt;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return (string)$this->hash;
    }


    public function jsonSerialize()
    {
        return [
            'hash' => (string)$this->hash,
            'salt' => (string)$this->salt
        ];
    }
}