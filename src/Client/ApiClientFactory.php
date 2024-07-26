<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Client;

use Artdevision\TelegramChannelListener\FileLoader\YamlConfigFileLoader;
use danog\MadelineProto\API;
use danog\MadelineProto\Exception;
use danog\MadelineProto\Settings;
use Symfony\Component\Config\FileLocator;

final class ApiClientFactory
{
    private const CONFIG = 'client.yml';
    public  const SESSION_NAME = 'session.tg-listener';

    /**
     * @throws Exception
     */
    public function __invoke(): API
    {
        $fileLocator = new FileLocator(__DIR__ . '/../../config');
        $config = (new YamlConfigFileLoader($fileLocator))->load(self::CONFIG);
        $settings = (new Settings())
            ->setAppInfo(
                (new Settings\AppInfo())
                    ->setApiId((int) $config['client']['api_id'])
                    ->setApiHash($config['client']['api_hash'])
            );
        return new API(self::SESSION_NAME, $settings);
    }
}
