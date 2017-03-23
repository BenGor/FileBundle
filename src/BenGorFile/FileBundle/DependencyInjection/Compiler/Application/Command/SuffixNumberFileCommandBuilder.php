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

use BenGorFile\File\Application\Command\Upload\SuffixNumberUploadFileCommand;
use BenGorFile\File\Application\Command\Upload\SuffixNumberUploadFileHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Suffix number upload file command builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class SuffixNumberFileCommandBuilder extends UploadFileCommandBuilder
{
    /**
     * {@inheritdoc}
     */
    public function build($file)
    {
        $this->register($file);

        $this->container->setAlias(
            $this->aliasDefinitionName($file),
            $this->definitionName($file)
        );

        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function register($file)
    {
        if ($this->configuration['upload_strategy'] != 'suffix_number') {
            return parent::register($file);
        }

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
     * Gets the FQCN of command.
     *
     * @return string
     */
    private function command()
    {
        return SuffixNumberUploadFileCommand::class;
    }

    /**
     * Gets the FQCN of command handler.
     *
     * @return string
     */
    private function handler()
    {
        return SuffixNumberUploadFileHandler::class;
    }
}
