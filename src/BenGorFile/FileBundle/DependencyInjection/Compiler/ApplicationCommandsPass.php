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

namespace BenGorFile\FileBundle\DependencyInjection\Compiler;

use BenGorFile\File\Application\Command\Upload\UploadFileCommand;
use BenGorFile\File\Application\Command\Upload\UploadFileHandler;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register application commands compiler pass.
 *
 * Command declaration via PHP allows more
 * flexibility with customization extend files.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApplicationCommandsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');

        foreach ($config['file_class'] as $key => $file) {
            $container->setDefinition(
                'bengor.file.application.command.upload_' . $key,
                new Definition(
                    UploadFileHandler::class, [
                        new Reference(
                            'bengor_file.file.filesystem'
                        ),
                        new Reference(
                            'bengor_file.file.repository'
                        ),
                        new Reference(
                            'bengor_file.file.factory'
                        ),
                    ]
                )
            )->addTag(
                'bengor_file_' . $key . '_command_bus_handler', [
                    'handles' => UploadFileCommand::class,
                ]
            );

            $container->setAlias(
                'bengor_file.' . $key . '.upload',
                'bengor.file.application.command.upload_' . $key
            );
        }
    }
}
