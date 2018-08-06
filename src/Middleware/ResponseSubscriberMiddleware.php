<?php
declare(strict_types=1);

namespace  App\Middleware;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;

class ResponseSubscriberMiddleware implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var string
     */
    private $apiVersion;

    public function __construct(RouterInterface $router, string $apiVersion)
    {
        $this->router = $router;
        $this->apiVersion = $apiVersion;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::RESPONSE => [
                'onKernelResponse', 0
            ]
        ];
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $response->headers->set('Api-Version', $this->apiVersion);

        if (strpos($event->getRequest()->getRequestUri(), 'swagger')) {
            return;
        }

        $content = $response->getContent();
        if(!$this->isJson($content)){
            return;
        }


        if ($response->getStatusCode() < Response::HTTP_BAD_REQUEST) {
            $data = "{\"data\":$content,\"errors\":null}";
        } else {
            $data = "{\"data\":null,\"errors\":$content}";
        }

        $response->setContent($data);

    }
    private function isJson($string) {
        if (is_string($string) && !is_int($string) && !is_array($string)) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        } else {
            return false;
        }
    }


}