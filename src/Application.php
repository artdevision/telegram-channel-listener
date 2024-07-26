<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener;

use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct(iterable $commands, string $name = 'UNKNOWN', string $version = 'UNKNOWN')
    {
        $this->name = $name;
        $this->version = $version;

        foreach ($commands as $command) {
            $this->add($command);
        }

        parent::__construct($name, $version);
    }
}
