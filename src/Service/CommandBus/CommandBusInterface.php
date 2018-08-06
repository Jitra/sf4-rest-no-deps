<?php
declare(strict_types=1);

namespace App\Service\CommandBus;


interface CommandBusInterface
{
    public function handle(CommandInterface $command): void;
}