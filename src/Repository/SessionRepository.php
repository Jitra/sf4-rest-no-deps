<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Carbon\Carbon;
use App\Entity\ValueObject\Bearer;
use App\Entity\ValueObject\Uuid;
use App\Entity\Session;

class SessionRepository extends BaseRepository
{
    protected const ENTITY = Session::class;

    public function findByToken(Bearer $bearer)
    {
        $session = $this->findOneBy(['token.token' => (string)$bearer]);

        return $session;
    }

    public function findOneByDeviceIdAndUserId(string $deviceId, Uuid $userId): ?Session
    {
        $query = $this->createQueryBuilder('session');
        $query->where('session.device.deviceId = :deviceId');
        $query->andWhere('session.user = :userId');
        $query->setParameter('deviceId', $deviceId);
        $query->setParameter('userId', $userId);
        $session = $query->getQuery()->getOneOrNullResult();

        return $session;
    }
}