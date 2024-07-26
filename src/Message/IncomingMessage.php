<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Message;

final class IncomingMessage
{
    public function __construct(
        private array $message,
        private array $chatInfo
    ) {
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    public function getChatInfo(): array
    {
        return $this->chatInfo;
    }
}
