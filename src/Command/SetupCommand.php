<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

#[AutoconfigureTag('app.commands')]
final class SetupCommand extends Command
{
    protected const CONFIG_FILE = __DIR__ . '/../../config/client.yml';

    public function __construct(protected Filesystem $filesystem, ?string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('tg-listener:setup')
            ->setDescription('Setup Base settings: API_ID API_HASH');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');
        $question = new Question('<info>Please Enter Telegram API ID:</info> ', false);

        if (($appId = $helper->ask($input, $output, $question)) === false) {
            throw new InvalidArgumentException('API ID is required');
        }
        $question = new Question('<info>Please Enter Telegram API HASH:</info> ', false);
        if (($appHash = $helper->ask($input, $output, $question)) === false) {
            throw new InvalidArgumentException('API HASH is required');
        }

        if ($this->filesystem->exists(self::CONFIG_FILE) === false) {
            $this->filesystem->touch(self::CONFIG_FILE);
        }

        $dumped = Yaml::dump([
            'client' => [
                'api_id' => $appId,
                'api_hash' => $appHash,
            ]
        ]);

        $this->filesystem->dumpFile(self::CONFIG_FILE, $dumped);

        $output->writeln('<info>Configuration Saved!</info>');

        return Command::SUCCESS;
    }
}
