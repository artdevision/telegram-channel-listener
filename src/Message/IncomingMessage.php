<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Message;

class IncomingMessage
{
    private array $message;
    private array $chatInfo;

    public function __construct(array $message, array $chatInfo)
    {
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
