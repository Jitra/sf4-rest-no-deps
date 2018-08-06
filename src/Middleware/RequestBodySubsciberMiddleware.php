<?php


namespace App\Middleware;

use App\Service\Rest\Decoder\DecoderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This listener handles Request body decoding.
 *
 * @author Lukas Kahwe Smith <smith@pooteeweet.org>
 *
 * @internal
 */
class RequestBodySubsciberMiddleware implements EventSubscriberInterface
{
    private $decoder;

    /**
     * Constructor.
     *
     * @param DecoderInterface $decoder
     */
    public function __construct(DecoderInterface $decoder) {
        $this->decoder = $decoder;
    }

    /**
     * Core request handler.
     *
     * @param GetResponseEvent $event
     *
     * @throws BadRequestHttpException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($this->isDecodeable($request)) {

            $content = $request->getContent();

            if (!empty($content)) {
                $data = $this->decoder->decode($content);
                if (is_array($data)) {
                    $request->request = new ParameterBag($data);
                } else {
                    $contentType = $request->headers->get('Content-Type');
                    throw new BadRequestHttpException('Invalid Content-Type: '.$contentType.' body received');
                }
            }
        }
    }

    /**
     * Check if we should try to decode the body.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function isDecodeable(Request $request)
    {
        if (!in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            return false;
        }

        return $this->isJsonType($request);
    }

    /**
     * Check if the content type indicates a form submission.
     *
     * @param Request $request
     *
     * @return bool
     */
    private function isJsonType(Request $request)
    {
        $contentTypeParts = explode(';', $request->headers->get('Content-Type'));

        return isset($contentTypeParts[0]) && in_array($contentTypeParts[0], ['application/json']);

    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => [
                'onKernelRequest', 0
            ]
        ];
    }
}
