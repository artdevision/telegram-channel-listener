<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\EventListener;

use Symfony\Component\Console\Event\ConsoleErrorEvent;

class ExceptionListener
{
    public function onConsoleException(ConsoleErrorEvent $event): void
    {
        $exception = $event->getError();
        $event->getOutput()->writeln(sprintf('<error>%s</error>', $exception->getMessage()));
    }
}
