<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Command;

use Artdevision\TelegramChannelListener\Client\ApiClientFactory;
use Artdevision\TelegramChannelListener\Client\ClientEventHandler;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wrep\Daemonizable\Command\EndlessCommand;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.commands')]
final class ListenCommand extends EndlessCommand
{
    protected function configure(): void
    {
        $this->setName('tg-listener:listen')
            ->setDescription('Listen Configured Telegram Channels');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        ClientEventHandler::startAndLoop(ApiClientFactory::SESSION_NAME);

        return self::SUCCESS;
    }
}
