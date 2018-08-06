<?php
declare(strict_types=1);

namespace App\Service\Rest\ParamConverter;


use App\Entity\ValueObject\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UuidConverter implements ParamConverterInterface
{

    /**
     * Stores the object in the request.
     *
     * @param ParamConverter $configuration Contains the name, class and options of the object
     *
     * @return bool True if the object has been successfully set, else false
     */
    public function apply(Request $request, ParamConverter $configuration)
    {
        $name = $configuration->getName();

        if (!$request->attributes->has($name)) {
            return false;
        }

        $value = $request->attributes->get($name);

        if (!$value && $configuration->isOptional()) {
            return false;
        }

        try {
            $id = Uuid::existing($value);
            $request->attributes->set($name, $id);
            return true;
        } catch (\InvalidArgumentException $e) {
            throw new NotFoundHttpException('Request uri contains invalid uuid');
        }
    }

    /**
     * Checks if the object is supported.
     *
     * @param ParamConverter $configuration
     * @return bool True if the object is supported, else false
     */
    public function supports(ParamConverter $configuration)
    {
        return $configuration->getClass() === Uuid::class;
    }
}