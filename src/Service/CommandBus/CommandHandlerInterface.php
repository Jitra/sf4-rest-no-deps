<?php
declare(strict_types=1);

namespace App\Service\CommandBus;

interface CommandHandlerInterface
{
    /**
     * Returns an array of of supported command class and callable method
     *
     * For instance:
     *
     *  array('App/CommandBus/DoSomethingCommand' => 'methodName')
     *
     * @return array The method name and command to be executed
     */
    public static function getSupportedCommands(): array;
}