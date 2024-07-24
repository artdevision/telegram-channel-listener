<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Yaml\Yaml;

#[AutoconfigureTag('app.commands')]
final class SetupChannelsCommand extends Command
{
    protected const CONFIG_FILE = __DIR__ . '/../../config/channels.yml';
    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem, ?string $name = null)
    {
        $this->filesystem = $filesystem;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setName('tg-listener:setup-channels')
            ->setDescription('Setup Listening Channels and Groups');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $helper = $this->getHelper('question');

        $question = new Question('<info>Please Enter Channels or Groups usernames or IDs separeted by \',\':</info> ', false);

        if (($channelsInput = $helper->ask($input, $output, $question)) !== false) {
            $channels = explode(',', $channelsInput);
            array_walk($channels, static function (&$value) {
                $value = trim($value);
            });
        }

        $dumped = Yaml::dump([
            'channels' => $channels ?? [],
        ]);

        $this->filesystem->dumpFile(self::CONFIG_FILE, $dumped);

        $output->writeln('<info>Configuration Saved!</info>');

        return self::SUCCESS;
    }
}
