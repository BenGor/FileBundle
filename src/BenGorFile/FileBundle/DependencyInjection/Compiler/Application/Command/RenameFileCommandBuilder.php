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

use BenGorFile\File\Application\Command\Rename\RenameFileCommand;
use BenGorFile\File\Application\Command\Rename\RenameFileHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Rename file command builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RenameFileCommandBuilder extends CommandBuilder
{
    /**
     * {@inheritdoc}
     */
    public function register($file)
    {
        $this->container->setDefinition(
            $this->definitionName($file),
            (new Definition(
                RenameFileHandler::class, [
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.domain.model.' . $file . '_filesystem'
                    ),
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.persistence.' . $file . '_repository'
                    ),
                ]
            ))->addTag(
                'bengor_file_' . $file . '_command_bus_handler', [
                    'handles' => RenameFileCommand::class,
                ]
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function definitionName($file)
    {
        return 'bengor.file.application.command.rename_' . $file;
    }

    /**
     * {@inheritdoc}
     */
    protected function aliasDefinitionName($file)
    {
        return 'bengor_file.' . $file . '.rename';
    }
}
