<?php
declare(strict_types=1);

namespace App\Middleware;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use App\Exception\ValidationException;
use Symfony\Component\Translation\TranslatorInterface;

class ExceptionResponseSubscriberMiddleware implements EventSubscriberInterface
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                'onKernelException', 0
            ]
        ];
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();


        if ($exception instanceof ValidationException) {
            $response = new JsonResponse($exception->getTranslatedErrors($this->translator), $exception->getStatusCode());
        } elseif ($exception instanceof UniqueConstraintViolationException) {
            $message = $this->translator->trans('Duplicated entry found');
            $response = new JsonResponse(['message' => $message], Response::HTTP_CONFLICT);
        } elseif ($exception instanceof HttpException) {
            $response = new JsonResponse(['message' => $exception->getMessage()], $exception->getStatusCode());
        } else {

            $response = new JsonResponse([
                'message' => $exception->getMessage() ? $exception->getMessage() : "Internal server error",
                'exception' => $exception
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $event->setResponse($response);
        return;
    }
}