<?php

declare(strict_types=1);

namespace N1ebieski\KSEFClient\Factories;

use Composer\InstalledVersions;
use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

final class CacheFactory extends AbstractFactory
{
    /**
     * @var string
     */
    private const NAMESPACE = 'ksef-php-client';

    public static function make(): ?CacheInterface
    {
        if (class_exists(InstalledVersions::class) === false) {
            return null;
        }

        return match (true) {
            InstalledVersions::isInstalled('symfony/cache') => self::makeSymfonyCache(),
            default => null
        };
    }

    private static function makeSymfonyCache(): CacheInterface
    {
        return new Psr16Cache(new FilesystemAdapter(self::NAMESPACE));
    }
}
