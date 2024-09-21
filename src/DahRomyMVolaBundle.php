<?php

namespace DahRomy\MVola;

use DahRomy\MVola\DependencyInjection\DahRomyMVolaExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DahRomyMVolaBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        if (null === $this->extension) {
            $this->extension = new DahRomyMVolaExtension();
        }

        return $this->extension;
    }
}
