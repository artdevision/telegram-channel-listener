<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Client;

use Artdevision\TelegramChannelListener\Facade\AppFacade;
use Artdevision\TelegramChannelListener\Message\IncomingMessage;
use danog\MadelineProto\EventHandler\Filter\Combinator\FiltersAnd;
use danog\MadelineProto\EventHandler\Filter\FilterIncoming;
use danog\MadelineProto\EventHandler\Message;
use danog\MadelineProto\SimpleEventHandler;
use danog\MadelineProto\EventHandler\Attributes\Handler;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class ClientEventHandler extends SimpleEventHandler
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ExceptionInterface
     */
    #[Handler]
    #[FiltersAnd(
        new FilterIncoming(),
        new FilterChannelsConfig(new ChannelsConfiguration()))
    ]
    public function handleMessage(Message $message): void
    {
        $chatInfo = $this->getInfo($message->chatId);

        $container = AppFacade::getContainer();

        /** @var MessageBusInterface $bus */
        $bus = $container->get('messenger');

        $bus?->dispatch(new IncomingMessage((array) $message, $chatInfo));
    }
}
