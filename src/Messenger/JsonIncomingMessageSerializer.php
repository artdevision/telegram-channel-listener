<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Messenger;

use Artdevision\TelegramChannelListener\Message\IncomingMessage;
use JsonException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\StampInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

final class JsonIncomingMessageSerializer implements SerializerInterface
{
    /**
     * @throws JsonException
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'];
        $headers = $encodedEnvelope['headers'];

        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);
        $message = new IncomingMessage($data['message'] ?? [], $data['chatInfo'] ?? []);

        $stamps = [];
        if (isset($headers['stamps'])) {
            $stamps = unserialize($headers['stamps'], ['allowed_classes' => [StampInterface::class]]);
        }

        return new Envelope($message, $stamps);
    }

    /**
     * @throws JsonException
     */
    public function encode(Envelope $envelope): array
    {
        $message = $envelope->getMessage();

        $allStamps = [];
        foreach ($envelope->all() as $stamps) {
            $allStamps = array_merge($allStamps, $stamps);
        }

        if ($message instanceof IncomingMessage) {
            return [
                'body' => json_encode([
                    'message' => $message->getMessage(),
                    'chatInfo' => $message->getChatInfo(),
                ], JSON_THROW_ON_ERROR),
                'headers' => [
                    'stamps' => serialize($allStamps)
                ]
            ];
        }

        return [];
    }
}
