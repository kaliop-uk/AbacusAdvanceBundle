<?php

namespace Abacus\AdvanceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class AbacusAdvanceExtension extends Extension
{
    const API_PARAMETER_PATTERN = 'advance_api.%s.%s';

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $this->setApiActions($container, $config['api']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('parameters.yml');
    }

    /**
     * @param ContainerBuilder $container
     * @param $apiConfig
     */
    private function setApiActions(ContainerBuilder $container, $apiConfig)
    {
        foreach ($apiConfig as $site => $siteConfiguration) {
            foreach ($siteConfiguration['actions'] as $action => $serviceId) {
                $container->setParameter(sprintf(self::API_PARAMETER_PATTERN, $site, $action), $serviceId);
            }
        }
    }
}
