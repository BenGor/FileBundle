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

use BenGorFile\File\Application\Query\FilterFilesHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Filters files query builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FilterFilesQueryBuilder extends QueryBuilder
{
    /**
     * {@inheritdoc}
     */
    public function register($file)
    {
        $this->container->setDefinition(
            $this->definitionName($file),
            new Definition(
                FilterFilesHandler::class, [
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
        return 'bengor.file.application.query.filter_' . $file . 's';
    }

    /**
     * {@inheritdoc}
     */
    protected function aliasDefinitionName($file)
    {
        return 'bengor_file.filter_' . $file . 's_query';
    }
}
