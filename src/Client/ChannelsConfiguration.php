<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Client;

use Artdevision\TelegramChannelListener\FileLoader\YamlConfigFileLoader;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;

final class ChannelsConfiguration
{
    private const CONFIG = 'channels.yml';
    private readonly array $channels;

    public function __construct()
    {
        $fileLocator = new FileLocator(__DIR__ . '/../../config');
        $config = (new YamlConfigFileLoader($fileLocator))->load(self::CONFIG);
        if ($this->supports($config)) {
            $this->channels = is_array($config['channels']) ? $config['channels'] : [$config['channels']];
        } else {
            throw new InvalidConfigurationException('Channels not configured');
        }
    }

    public function getChannels(): array
    {
        return $this->channels;
    }

    protected function supports(array $config): bool
    {
        return array_key_exists('channels', $config);
    }
}
