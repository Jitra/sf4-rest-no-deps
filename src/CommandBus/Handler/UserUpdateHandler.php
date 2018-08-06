<?php
declare(strict_types=1);

namespace App\CommandBus\Handler;

use App\CommandBus\UserUpdateCommand;
use App\Entity\User;
use App\Exception\NotUniqueException;
use App\Repository\UserRepository;
use App\Service\CommandBus\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserUpdateHandler implements CommandHandlerInterface
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

    /**
     * @param UserUpdateCommand $command
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function handle(UserUpdateCommand $command)
    {
        /** @var User $user */
        $user = $this->userRepository->get($command->getId());

        $user->setFirstName($command->getFirstName());
        $user->setLastName($command->getLastName());
        $user->setPhoneNumber($command->getPhoneNumber());
        $user->setEmail($command->getEmailAddress());

        $this->validateUnique($user);

        $this->entityManager->persist($user);
    }

    protected function validateUnique(User $user)
    {
        $existingUser = $this->userRepository->findOneByEmail($user->getEmail());
        if ($existingUser && (string)$existingUser->getId() !== (string)$user->getId()) {
            throw new NotUniqueException('phone', (string)$user->getEmail());
        }
    }

    public static function getSupportedCommands(): array
    {
        return [UserUpdateCommand::class => 'handle'];
    }
}