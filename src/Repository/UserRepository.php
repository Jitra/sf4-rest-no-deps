<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Entity\ValueObject\EmailAddress;

class UserRepository extends BaseRepository
{
    protected const ENTITY = User::class;

    public function findOneByEmail(EmailAddress $email):? User
    {
        $query = $this->createQueryBuilder('user')
            ->andWhere('user.email.email = :email')
            ->setParameter('email', (string)$email);

        $user = $query->getQuery()->getOneOrNullResult();
        return $user;
    }
}
