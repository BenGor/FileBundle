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

namespace BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Command;

use BenGorFile\File\Application\Command\Upload\ByHashUploadFileCommand;
use BenGorFile\File\Application\Command\Upload\ByHashUploadFileHandler;
use BenGorFile\File\Application\Command\Upload\UploadFileCommand;
use BenGorFile\File\Application\Command\Upload\UploadFileHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Upload file command builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UploadFileCommandBuilder extends CommandBuilder
{
    /**
     * {@inheritdoc}
     */
    public function register($file)
    {
        $this->container->setDefinition(
            $this->definitionName($file),
            (new Definition(
                $this->handler(), [
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.domain.model.' . $file . '_filesystem'
                    ),
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.persistence.' . $file . '_repository'
                    ),
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.persistence.' . $file . '_specification_factory'
                    ),
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.domain.model.' . $file . '_factory'
                    ),
                ]
            ))->addTag(
                'bengor_file_' . $file . '_command_bus_handler', [
                    'handles' => $this->command(),
                ]
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function definitionName($file)
    {
        return 'bengor.file.application.command.upload_' . $file;
    }

    /**
     * {@inheritdoc}
     */
    protected function aliasDefinitionName($file)
    {
        return 'bengor_file.' . $file . '.upload';
    }

    /**
     * Gets the FQCN of command.
     *
     * @return string
     */
    private function command()
    {
        return $this->configuration['upload_strategy'] === 'default'
            ? UploadFileCommand::class
            : ByHashUploadFileCommand::class;
    }

    /**
     * Gets the FQCN of command handler.
     *
     * @return string
     */
    private function handler()
    {
        return $this->configuration['upload_strategy'] === 'default'
            ? UploadFileHandler::class
            : ByHashUploadFileHandler::class;
    }
}
