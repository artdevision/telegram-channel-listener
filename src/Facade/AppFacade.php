<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Facade;

use Psr\Container\ContainerInterface;
use RuntimeException;

class AppFacade
{
    private static ?self $instance = null;
    private static ?ContainerInterface $container = null;

    private function __construct(ContainerInterface $container)
    {
        self::$container = $container;
    }

    public static function init(ContainerInterface $container): self
    {
        if (null === self::$instance) {
            self::$instance = new self($container);
        }

        return self::$instance;
    }

    public static function getContainer(): ?ContainerInterface
    {
        if (self::$instance === null) {
            throw new RuntimeException('Facade is not instantiated');
        }

        return self::$container;
    }
}
