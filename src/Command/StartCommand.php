<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Command;

use Artdevision\TelegramChannelListener\Client\ApiClientFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.commands')]
final class StartCommand extends Command
{
    public function __construct(protected ApiClientFactory $clientFactory, string $name = null)
    {
        $this->clientFactory = $clientFactory;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('tg-listener:start')
            ->setDescription('Startup and Login in Telegram Account');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $client = $this->clientFactory->__invoke();
        $client->start();
        return Command::SUCCESS;
    }
}
