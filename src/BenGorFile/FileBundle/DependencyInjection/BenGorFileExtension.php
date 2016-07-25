<?php

/*
 * This file is part of the BenGorFile package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorFile\FileBundle\DependencyInjection;

use BenGorFile\FileBundle\Twig\DownloadExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * BenGor file bundle extension class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config/services'));

        $loader->load('routing.yml');

        $container->setParameter('bengor_file.config', $config);

        foreach ($config['file_class'] as $key => $file) {
            $container->setDefinition(
                'bengor_file.file_bundle.twig.view_extension_' . $key,
                (new Definition(DownloadExtension::class))->addTag(
                    'twig.extension'
                )
            );
        }
    }
}
