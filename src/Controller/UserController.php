<?php
declare(strict_types=1);

namespace App\Controller;

use App\CommandBus\UserCreateCommand;
use App\CommandBus\UserDeleteCommand;
use App\CommandBus\UserUpdateCommand;
use App\Entity\ValueObject\Uuid;
use App\Form\UserData;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends BaseController
{

    /**
     * @Route("/user/{id}", name="user_one", methods={"GET"}, requirements={
     *     "id" = "[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}"
     * })
     * @param Uuid $id
     * @param UserRepository $userRepository
     * @return JsonResponse
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function getOneAction(Uuid $id, UserRepository $userRepository): JsonResponse
    {
        $user = $userRepository->get($id);
        return $this->serializedResponse($user, ['user']);
    }

    /**
     * @Route("/user", name="user_list", methods={"GET"})
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function getAllAction(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        return $this->serializedResponse($users, ['user']);
    }

    /**
     * @Route("/user", name="user_create", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @return JsonResponse
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function createAction(Request $request, UserRepository $userRepository): JsonResponse
    {
        /** @var UserData $userData */
        $userData = $this->handleRequest($request, UserFormType::class, [
            'validation_groups' => UserFormType::CREATE_GROUP
        ]);

        $command = new UserCreateCommand($id = new Uuid(), $userData->email, $userData->password);
        $command->setFirstName($userData->firstName);
        $command->setLastName($userData->lastName);
        $command->setPhoneNumber($userData->phoneNumber);

        $this->commandBus->handle($command);

        $this->getEntityManager()->flush();

        return $this->getOneAction($id, $userRepository);
    }

    /**
     * @Route("/user/{id}", name="user_update", methods={"PUT"}, requirements={
     *     "id" = "[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}"
     * })
     * @param Uuid $id
     * @param Request $request
     * @param UserRepository $userRepository
     * @return JsonResponse
     * @throws \Doctrine\ORM\EntityNotFoundException
     */
    public function updateAction(Uuid $id, Request $request, UserRepository $userRepository): JsonResponse
    {
        /** @var UserData $userData */
        $userData = $this->handleRequest($request, UserFormType::class);

        $command = new UserUpdateCommand($id, $userData->email);
        $command->setFirstName($userData->firstName);
        $command->setLastName($userData->lastName);
        $command->setPhoneNumber($userData->phoneNumber);

        $this->commandBus->handle($command);

        $this->getEntityManager()->flush();

        return $this->getOneAction($id, $userRepository);
    }

    /**
     * @Route("/user/{id}", name="user_update", methods={"DELETE"}, requirements={
     *     "id" = "[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}"
     * })
     * @param Uuid $id
     * @return JsonResponse
     */
    public function deleteAction(Uuid $id): JsonResponse
    {
        $this->commandBus->handle(new UserDeleteCommand($id));
        $this->getEntityManager()->flush();

        return new JsonResponse();
    }
}