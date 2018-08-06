<?php
declare(strict_types=1);

namespace App\Service\CommandBus;
/**
 * Class SuperSimpleCommandBus
 *
 * For real usage of CommandBus would go add:
 * https://github.com/thephpleague/tactician-bundle
 */
class SuperSimpleCommandBus implements CommandBusInterface
{
    /**
     * @var CommandHandlerInterface[]
     */
    private $handlers;

    public function __construct(iterable $handlers)
    {
        $this->handlers = $handlers;
    }

    public function handle(CommandInterface $command): void
    {
        $commandClass = get_class($command);

        foreach ($this->handlers as $handler) {
            $supportedCommands = $handler::getSupportedCommands();
            if (array_key_exists($commandClass, $supportedCommands)) {
                $handleMethod = $supportedCommands[$commandClass];
                $handler->{$handleMethod}($command);
            }
        }
    }
}