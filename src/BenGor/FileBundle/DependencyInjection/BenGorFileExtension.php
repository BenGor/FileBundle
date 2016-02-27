<?php

/*
 * This file is part of the BenGorFileBundle bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\FileBundle\DependencyInjection;

use BenGor\FileBundle\Form\Type\FileIdType;
use BenGor\FileBundle\Form\Type\FileType;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
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
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));

        $loader->load('services.yml');
        $this->loadForms($container, $config);
        $container->setParameter('bengor_file.config', $config);
    }

    /**
     * Loads form types as a service inside Symfony DIC.
     *
     * @param ContainerBuilder $container The container
     * @param array            $config    The bengor user configuration tree
     */
    private function loadForms(ContainerBuilder $container, $config)
    {
        foreach ($config['file_class'] as $key => $file) {
            $container->setDefinition(
                'bengor.file_bundle.form.type.file_id',
                (new Definition(
                    FileIdType::class, [
                        new Reference('bengor_file.' . $file['persistence'] . '_' . $key . '_repository'),
                    ]
                ))->addTag('form.type')
            );

            $container->setDefinition(
                'bengor.file_bundle.form.type.file',
                (new Definition(
                    FileType::class, [
                        new Reference('bengor_file.' . $file['persistence'] . '_' . $key . '_repository'),
                    ]
                ))->addTag('form.type')
            );
        }
    }
}
