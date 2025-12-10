<?php

declare(strict_types=1);

namespace Infrastructure\Symfony;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends BaseKernel
{
    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
        ];
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    public function getCacheDir(): string
    {
        return __DIR__ . '/var/cache/' . $this->environment;
    }

    public function getLogDir(): string
    {
        return __DIR__ . '/var/log';
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $configDir = __DIR__ . '/config';
        $loader->load($configDir . '/services.yaml');
        $loader->load($configDir . '/packages/framework.yaml');
    }
}
