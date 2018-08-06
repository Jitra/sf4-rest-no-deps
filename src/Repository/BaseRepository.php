<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\ValueObject\Uuid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\EntityNotFoundException;

/**
 * Class BaseRepository
 * @package App\Repository
 */
abstract class BaseRepository extends ServiceEntityRepository
{
    const SORT_ASC = 'ASC';
    const SORT_DESC = 'DESC';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, static::ENTITY);
    }

    /**
     * @param Uuid $id
     * @return null|object
     * @throws EntityNotFoundException
     */
    public function get(Uuid $id)
    {
        if (null === ($entity = $this->find($id))) {

            $parts = explode("\\", $this->_entityName);
            $name = end($parts);
            throw new EntityNotFoundException("$name with id: $id doesn't exist");
        }


        return $entity;
    }

}