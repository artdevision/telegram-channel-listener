<?php
declare(strict_types=1);

namespace Artdevision\TelegramChannelListener\FileLoader;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

final class YamlConfigFileLoader extends FileLoader
{
    public function load(mixed $resource, ?string $type = null): mixed
    {
        return Yaml::parse(file_get_contents($this->locator->locate($resource)));
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return is_string($resource)
            && in_array(pathinfo($resource, PATHINFO_EXTENSION), ['yml', 'yaml'], true);
    }
}
