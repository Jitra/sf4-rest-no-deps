<?php
declare(strict_types=1);

namespace App\Entity;

use Carbon\Carbon;
use Doctrine\Common\Collections\Collection;
use App\Entity\ValueObject\Hash;
use App\Entity\ValueObject\PasswordHash;
use App\Entity\ValueObject\SaltHash;
use App\Entity\ValueObject\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
abstract class BaseUser implements UserInterface
{
    /**
     * @ORM\Column(type = "uuid")
     * @ORM\Id
     * @var Uuid
     */
    protected $id;
    /**
     * @ORM\Embedded(class = "App\Entity\ValueObject\PasswordHash")
     * @var null|PasswordHash
     */
    protected $password;

    /**
     * @ORM\Embedded(class = "App\Entity\ValueObject\Hash")
     * @var null|Hash
     */
    protected $confirmationToken;

    /**
     * @ORM\Column(type = "carbon", nullable=true)
     * @var null|Carbon
     */
    protected $passwordRequestedAt;

    /**
     * @ORM\Embedded(class = "App\Entity\ValueObject\Hash")
     * @var null|Hash
     */
    protected $passwordResetHash;

    /**
     * @ORM\OneToMany(targetEntity="Session", mappedBy="user", cascade={"remove"})
     * @var Session[]|Collection<Session>
     */
    protected $sessions;

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPassword(): ?PasswordHash
    {
        return $this->password;
    }

    public function getConfirmationToken():? Hash
    {
        return $this->confirmationToken;
    }

    public function getPasswordRequestedAt(): ?Carbon
    {
        return $this->passwordRequestedAt;
    }

    public function getSalt(): ?SaltHash
    {
        return $this->password? $this->password->getSalt(): null;
    }

    public function getPasswordResetHash(): ?Hash
    {
        return $this->passwordResetHash;
    }

    public function setPassword(PasswordHash $password): void
    {
        $this->password = $password;
    }

    public function setConfirmationToken(?Hash $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function setPasswordRequestedAt(?Carbon $passwordRequestedAt)
    {
        $this->passwordRequestedAt = $passwordRequestedAt;
    }

    public function setPasswordResetHash(?Hash $passwordResetHash): void
    {
        $this->passwordResetHash = $passwordResetHash;
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
        return;
    }

    /**
     * @inheritdoc
     */
    abstract public function getRoles();

    /**
     * @inheritdoc
     */
    abstract public function getUsername();

    /**
     * @return Collection|Session[]
     */
    public function getSessions()
    {
        return array_values($this->sessions->toArray());
    }

    public function getNotificationPushTokens()
    {
        $tokens = array_map(function (Session $session) {
            return $session->getDevice()->getPushNotificationToken();
        }, $this->getSessions());

        return array_filter($tokens, function ($token) {
            return !!$token;
        });
    }
}