<?php
declare(strict_types = 1);

namespace App\CommandBus;

use App\Entity\ValueObject\Uuid;
use App\Service\CommandBus\CommandInterface;

class UserDeleteCommand implements CommandInterface
{
    /**
     * @var Uuid
     */
    protected $id;


    public function __construct(Uuid $id)
    {
        $this->id = $id;
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }
}