<?php
declare(strict_types=1);

namespace App\Controller;

use App\Service\CommandBus\CommandBusInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\BaseRepository;
use App\Entity\Session;
use App\Exception\InvalidFormException;
use Symfony\Component\Serializer\SerializerInterface;

abstract class BaseController extends Controller
{
    /**
     * @var CommandBusInterface
     */
    protected $commandBus;
    /**
     * @var SerializerInterface
     */
    protected $serializer;

    public function __construct(CommandBusInterface $commandBus, SerializerInterface $serializer)
    {
        $this->commandBus = $commandBus;
        $this->serializer = $serializer;
    }

    protected function handleRequest(Request $request, string $form, array $options = [])
    {
        $form = $this->createForm($form, null, $options);
        $form->submit($request->request->all());

        if (!$form->isValid()) {
            throw new InvalidFormException($form);
        }

        return $data = $form->getData();
    }

    protected function paginate(Query $query, ?int $page, ?int $limit)
    {
        $paginator = new Paginator($query);
        if ($page && $limit) {
            $paginator->getQuery()->setFirstResult(($page - 1) * $limit)->setMaxResults($limit);
        }

        $items = $paginator->getIterator()->getArrayCopy();
        $total = $paginator->count();
        return $this->paginateArray($items, $page, $limit, $total);

    }

    protected function paginateArray(array $items, $currentPage, $limit, $total)
    {
        return $paginateArray = [
            'items' => $items,
            'currentPage' => $currentPage,
            'limit' => $limit,
            'total' => $total
        ];
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEntityManager(): ObjectManager
    {
        return $this->getDoctrine()->getManager();
    }

    protected function getRepository($className): BaseRepository
    {
        return $this->getEntityManager()->getRepository($className);
    }

    protected function getSession(): ?Session

    {
        $ret = $this->get('security.token_storage')->getToken()->getUser();
        return is_string($ret) ? null : $ret;
    }

    protected function serializedResponse($data, array $groups = [], int $status = 200, array $headers = [])
    {
        return JsonResponse::fromJsonString(
            $this->serializer->serialize($data, 'json', count($groups) == 0 ? [] : [
                'groups' => $groups
            ]), $status, $headers);
    }
}
