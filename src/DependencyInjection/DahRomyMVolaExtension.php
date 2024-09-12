<?php

namespace DahRomy\MVola\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class DahRomyMVolaExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $this->registerConfiguration($config, $container);
    }

    private function registerConfiguration(array $config, ContainerBuilder $container): void
    {
        $container->setParameter('mvola.environment', $config['environment']);
        $container->setParameter('mvola.merchant_number', $config['merchant_number']);
        $container->setParameter('mvola.company_name', $config['company_name']);
        $container->setParameter('mvola.consumer_key', $config['consumer_key']);
        $container->setParameter('mvola.consumer_secret', $config['consumer_secret']);
        $container->setParameter('mvola.auth_url', $config['auth_url']);
        $container->setParameter('mvola.max_retries', $config['max_retries']);
        $container->setParameter('mvola.retry_delay', $config['retry_delay']);
        $container->setParameter('mvola.cache_ttl', $config['cache_ttl']);
    }
}
