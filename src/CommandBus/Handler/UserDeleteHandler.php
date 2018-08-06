<?php
declare(strict_types=1);

namespace App\CommandBus\Handler;

use App\CommandBus\UserCreateCommand;
use App\CommandBus\UserDeleteCommand;
use App\Entity\User;
use App\Exception\NotUniqueException;
use App\Repository\UserRepository;
use App\Service\CommandBus\CommandHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;

class UserDeleteHandler implements CommandHandlerInterface
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
     * @param UserDeleteCommand $command
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function handle(UserDeleteCommand $command)
    {
        $user = $this->userRepository->get($command->getId());

        $this->entityManager->remove($user);
    }


    public static function getSupportedCommands(): array
    {
        return [UserDeleteCommand::class => 'handle'];
    }
}