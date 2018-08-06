<?php
declare(strict_types=1);

namespace App\Controller;

use App\CommandBus\UserLoginCommand;
use App\Entity\ValueObject\Bearer;
use App\Form\LoginData;
use App\Form\LoginFormType;
use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SessionController extends BaseController
{
    /**
     * @Route("/login", name="login", methods={"POST"})
     * @param Request $request
     * @param SessionRepository $sessionRepository
     * @return null|object
     */
    public function login(Request $request, SessionRepository $sessionRepository)
    {
        /** @var $data LoginData */
        $data = $this->handleRequest($request, LoginFormType::class);
        $loginCommand = new UserLoginCommand($data->login,$data->password, $bearer = new Bearer(), $data->deviceInfo);
        $loginCommand->setLocale($data->locale);
        $this->commandBus->handle($loginCommand);

        $this->getEntityManager()->flush();
        $session = $sessionRepository->findByToken($bearer);

        return $this->serializedResponse($session, ['session']);
    }
}