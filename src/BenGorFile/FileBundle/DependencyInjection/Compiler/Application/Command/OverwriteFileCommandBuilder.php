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

use BenGorFile\File\Application\Command\Overwrite\OverwriteFileCommand;
use BenGorFile\File\Application\Command\Overwrite\OverwriteFileHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Overwrite file command builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class OverwriteFileCommandBuilder extends CommandBuilder
{
    /**
     * {@inheritdoc}
     */
    public function register($file)
    {
        $this->container->setDefinition(
            $this->definitionName($file),
            (new Definition(
                OverwriteFileHandler::class, [
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.domain.model.' . $file . '_filesystem'
                    ),
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.persistence.' . $file . '_repository'
                    ),
                ]
            ))->addTag(
                'bengor_file_' . $file . '_command_bus_handler', [
                    'handles' => OverwriteFileCommand::class,
                ]
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function definitionName($file)
    {
        return 'bengor.file.application.command.overwrite_' . $file;
    }

    /**
     * {@inheritdoc}
     */
    protected function aliasDefinitionName($file)
    {
        return 'bengor_file.' . $file . '.overwrite';
    }
}
