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

namespace BenGor\FileBundle\DependencyInjection\Compiler;

use BenGor\File\Infrastructure\Filesystem\Gaufrette\GaufretteFilesystem;
use BenGor\File\Infrastructure\Filesystem\Symfony\SymfonyFilesystem;
use Gaufrette\Filesystem;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register filesystem services compiler pass.
 *
 * Service declaration via PHP allows more
 * flexibility with customization extend users.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FilesystemServicesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');

        foreach ($config['file_class'] as $key => $file) {
            $method = 'loadGaufretteFilesystem';
            if (null === $file['filesystem']['gaufrette']) {
                $method = 'loadSymfonyFilesystem';
                if (null === $file['filesystem']['symfony']) {
                    throw new \RuntimeException('Please, configure al least one of two allowed filesystems');
                }
            }
            $this->$method($container, $key, $file);
        }
    }

    /**
     * Loads the Gaufrette filesystem repository.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $key       The name of file type
     * @param array            $file      File configuration tree
     */
    private function loadGaufretteFilesystem(ContainerBuilder $container, $key, $file)
    {
        if (false === $container->hasDefinition('knp_gaufrette.filesystem_map')) {
            throw new \RuntimeException(
                'It seems that the Gaufrette filesystem map service does not loaded, ' .
                'is GaufretteBundle installed and enabled in the kernel?'
            );
        }
        $container->setDefinition(
            'bengor.file_bundle.filesystem.gaufrette.' . $key,
            (new Definition(
                Filesystem::class, [
                    $file['filesystem']['gaufrette'],
                ]
            ))->setFactory([
                new Reference('knp_gaufrette.filesystem_map'),
                'get',
            ])
        );
        $container->setDefinition(
            'bengor.file.infrastructure.filesystem.gaufrette.' . $key,
            new Definition(
                GaufretteFilesystem::class, [
                    new Reference('bengor.file_bundle.filesystem.gaufrette.' . $key),
                ]
            )
        );
    }

    /**
     * Loads the Symfony filesystem.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $key       The name of file type
     * @param array            $file      File configuration tree
     */
    private function loadSymfonyFilesystem(ContainerBuilder $container, $key, $file)
    {
        $container->setDefinition(
            'bengor.file.infrastructure.filesystem.symfony.' . $key,
            new Definition(
                SymfonyFilesystem::class, [
                    $file['filesystem']['symfony'],
                    new Reference('filesystem'),
                ]
            )
        );
    }
}
