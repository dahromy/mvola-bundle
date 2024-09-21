<?php

namespace DahRomy\MVola;

use DahRomy\MVola\DependencyInjection\DahRomyMVolaExtension;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DahRomyMVolaBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new DahRomyMVolaExtension();
    }

    /**
     * @return mixed
     */
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    /**
     * @return ContainerInterface|null
     */
    public function getContainer(): ?ContainerInterface
    {
        return $this->container;
    }
}
