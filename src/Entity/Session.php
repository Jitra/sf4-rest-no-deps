<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\ValueObject\Bearer;
use App\Entity\ValueObject\DeviceInfo;
use App\Entity\ValueObject\Locale;
use App\Entity\ValueObject\Uuid;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="session")
 * @ORM\HasLifecycleCallbacks()
 */
class Session implements UserInterface
{
    /**
     * @ORM\Column(type = "uuid")
     * @ORM\Id
     * @var Uuid
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="BaseUser", inversedBy="sessions")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * @var UserInterface
     */
    protected $user;

    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\DeviceInfo")
     * @var DeviceInfo
     */
    protected $device;

    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Bearer")
     * @var Bearer
     */
    protected $token;
    /**
     * @ORM\Embedded(class="App\Entity\ValueObject\Locale")
     * @var Locale
     */
    protected $locale;

    public function __construct(Uuid $id, UserInterface $user, Bearer $token, DeviceInfo $device, Locale $locale)
    {
        $this->id = $id;
        $this->user = $user;
        $this->token = $token;
        $this->device = $device;
        $this->locale = $locale;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @return UserInterface
     */
    public function getUser(): UserInterface
    {
        return $this->user;
    }

    /**
     * @return DeviceInfo
     */
    public function getDevice(): DeviceInfo
    {
        return $this->device;
    }

    /**
     * @return Bearer
     */
    public function getToken(): Bearer
    {
        return $this->token;
    }


    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return $this->user->getRoles();
    }

    /**
     * @inheritdoc
     */
    public function getPassword()
    {
        return $this->user->getPassword();
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
        return $this->user->getSalt();
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->user->getUsername();
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {
        $this->user->eraseCredentials();
    }

    /**
     * @param Bearer $token
     */
    public function setToken(Bearer $token): void
    {
        $this->token = $token;
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
     * @param DeviceInfo $device
     */
    public function setDevice(DeviceInfo $device): void
    {
        $this->device = $device;
    }
}