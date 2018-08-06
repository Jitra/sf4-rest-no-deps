<?php
declare(strict_types=1);

namespace App\CommandBus\Handler;

use App\CommandBus\UserLoginCommand;
use App\Entity\User;
use App\Entity\ValueObject\EmailAddress;
use App\Repository\UserRepository;
use App\Service\CommandBus\CommandHandlerInterface;
use App\Service\CommandBus\CommandInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SessionRepository;
use App\Entity\Session;
use App\Entity\ValueObject\Uuid;
use App\Exception\InvalidCredentialsException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserLoginHandler implements CommandHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;
    /**
     * @var SessionRepository
     */
    private $sessionRepository;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordEncoderInterface $passwordEncoder,
        SessionRepository $sessionRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->passwordEncoder = $passwordEncoder;
        $this->sessionRepository = $sessionRepository;
    }

    public function handle(UserLoginCommand $command): void
    {
        $user = $this->getUser($command->getEmail());
        $this->validatePasswordOrThrowException($user, $command->getPassword());

        $deviceInfo = $command->getDeviceInfo();

        if ($deviceInfo->getDeviceId() && $deviceInfo->isMobile()) {
            // return existing session with updated data for this DeviceId and User
            /** @var Session $session */
            $session = $this->sessionRepository->findOneByDeviceIdAndUserId(
                $deviceInfo->getDeviceId(),
                $user->getId()
            );

            if ($session) {
                $this->updateSession($session, $command);
                return;
            }
        }

        // or create new if doesn't exist
        $session = new Session(
            new Uuid(), $user, $command->getBearer(), $deviceInfo, $command->getLocale()
        );

        $this->em->persist($session);
    }

    protected function validatePasswordOrThrowException(?User $user, string $password)
    {
        if ($user == null || !$this->passwordEncoder->isPasswordValid($user, $password)) {
            throw new InvalidCredentialsException();
        }

    }

    protected function updateSession(Session $session, UserLoginCommand $command)
    {
        $session->setToken($command->getBearer());
        $session->setDevice($command->getDeviceInfo());
        $session->setLocale($command->getLocale());
    }

    protected function getUser(EmailAddress $email): ?User
    {
        $user = $this->userRepository->findOneByEmail($email);

        return $user;
    }

    public static function getSupportedCommands(): array
    {
        return [UserLoginCommand::class => 'handle'];
    }
}