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

namespace BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Query;

use BenGorFile\File\Application\Query\FileOfNameHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * File of name query builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileOfNameQueryBuilder extends QueryBuilder
{
    /**
     * {@inheritdoc}
     */
    public function register($file)
    {
        $this->container->setDefinition(
            $this->definitionName($file),
            new Definition(
                FileOfNameHandler::class, [
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.persistence.' . $file . '_repository'
                    ),
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.persistence.' . $file . '_specification_factory'
                    ),
                    $this->container->getDefinition(
                        'bengor.file.application.data_transformer.' . $file . '_dto'
                    ),
                ]
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function definitionName($file)
    {
        return 'bengor.file.application.query.' . $file . '_of_name';
    }

    /**
     * {@inheritdoc}
     */
    protected function aliasDefinitionName($file)
    {
        return 'bengor_file.' . $file . '.by_name_query';
    }
}
