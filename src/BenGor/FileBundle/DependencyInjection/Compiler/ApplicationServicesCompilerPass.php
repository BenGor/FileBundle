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

use BenGor\File\Application\Service\OverwriteFileService;
use BenGor\File\Application\Service\UploadFileService;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Register application services compiler pass.
 *
 * Service declaration via PHP allows more
 * flexibility with customization extend users.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApplicationServicesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');

        foreach ($config['file_class'] as $key => $file) {
            $container->setDefinition(
                'bengor.file.application.service.upload_' . $key,
                new Definition(
                    UploadFileService::class, [
                        $this->getFilesystemDefinition($container, $key, $file),
                        $container->getDefinition(
                            'bengor.file.infrastructure.persistence.' . $file['persistence'] . '.' . $key . '_repository'
                        ),
                        $container->getDefinition(
                            'bengor.file.infrastructure.domain.model.' . $key . '_factory'
                        ),
                    ]
                )
            );
            $container->setDefinition(
                'bengor.file.application.service.overwrite_' . $key,
                new Definition(
                    OverwriteFileService::class, [
                        $this->getFilesystemDefinition($container, $key, $file),
                        $container->getDefinition(
                            'bengor.file.infrastructure.persistence.' . $file['persistence'] . '.' . $key . '_repository'
                        ),
                    ]
                )
            );
            $container->setDefinition(
                'bengor.file.application.service.remove_' . $key,
                new Definition(
                    OverwriteFileService::class, [
                        $this->getFilesystemDefinition($container, $key, $file),
                        $container->getDefinition(
                            'bengor.file.infrastructure.persistence.' . $file['persistence'] . '.' . $key . '_repository'
                        ),
                    ]
                )
            );
        }
    }

    /**
     * Gets the filesystem definition.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $key       The name of file type
     * @param array            $file      File configuration tree
     *
     * @return Definition
     */
    private function getFilesystemDefinition(ContainerBuilder $container, $key, $file)
    {
        $name = 'gaufrette';
        if (null === $file['filesystem']['gaufrette']) {
            $name = 'symfony';
        }

        return $container->getDefinition('bengor.file.infrastructure.filesystem.' . $name . '.' . $key);
    }
}
