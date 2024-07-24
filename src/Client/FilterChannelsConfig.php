<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Client;

use Attribute;
use danog\MadelineProto\EventHandler;
use danog\MadelineProto\EventHandler\Filter\Filter;
use danog\MadelineProto\EventHandler\Message\ChannelMessage;
use danog\MadelineProto\EventHandler\Message\GroupMessage;
use danog\MadelineProto\EventHandler\Update;

#[Attribute(Attribute::TARGET_METHOD)]
final class FilterChannelsConfig extends Filter
{
    private readonly array $channels;
    private EventHandler $handler;

    public function __construct(ChannelsConfiguration $configuration)
    {
        $this->channels = $configuration->getChannels();
    }

    public function initialize(EventHandler $API): Filter
    {
        $this->handler = $API;
        return $this;
    }

    public function apply(Update $update): bool
    {
        if ($update instanceof ChannelMessage || $update instanceof GroupMessage) {
            $chatInfo = $this->handler->getInfo($update->chatId);
            if (isset($chatInfo['channel_id']) && in_array($chatInfo['channel_id'], $this->channels, true)) {
                return true;
            }
            if(isset($chatInfo['Chat']['username']) && in_array($chatInfo['Chat']['username'], $this->channels, false)) {
                return true;
            }
        }

        return false;
    }
}
