<?php
declare(strict_types=1);

namespace App\CommandBus\Handler;

use App\CommandBus\UserCreateCommand;
use App\Entity\User;
use App\Exception\NotUniqueException;
use App\Repository\UserRepository;
use App\Service\CommandBus\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserCreateHandler implements CommandHandlerInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var UserRepository
     */
    protected $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function handle(UserCreateCommand $command)
    {
        $user = new User(
            $command->getId(),
            $command->getEmailAddress(),
            $command->getPasswordHash()
        );
        $user->setFirstName($command->getFirstName());
        $user->setLastName($command->getLastName());
        $user->setPhoneNumber($command->getPhoneNumber());
        $this->validateUnique($user);

        $this->entityManager->persist($user);
    }

    protected function validateUnique(User $user): void
    {
        if ($this->userRepository->findOneByEmail($user->getEmail())) {
            throw new NotUniqueException('email', (string)$user->getEmail());
        }
    }

    public static function getSupportedCommands(): array
    {
        return [UserCreateCommand::class => 'handle'];
    }
}