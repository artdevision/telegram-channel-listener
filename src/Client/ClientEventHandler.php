<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Client;

use Artdevision\TelegramChannelListener\Facade\AppFacade;
use danog\MadelineProto\EventHandler\Filter\Combinator\FiltersAnd;
use danog\MadelineProto\EventHandler\Filter\FilterIncoming;
use danog\MadelineProto\EventHandler\Message;
use danog\MadelineProto\SimpleEventHandler;
use danog\MadelineProto\EventHandler\Attributes\Handler;
use Symfony\Component\Messenger\MessageBusInterface;

final class ClientEventHandler extends SimpleEventHandler
{
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

        $bus->dispatch();

        $this->logger($chatInfo);
    }
}
