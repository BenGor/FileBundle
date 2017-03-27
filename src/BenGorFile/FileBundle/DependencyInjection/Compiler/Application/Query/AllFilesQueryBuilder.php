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

use BenGorFile\File\Application\Query\AllFilesHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * All files query builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class AllFilesQueryBuilder extends QueryBuilder
{
    /**
     * {@inheritdoc}
     */
    public function register($file)
    {
        $this->container->setDefinition(
            $this->definitionName($file),
            new Definition(
                AllFilesHandler::class, [
                    $this->container->getDefinition(
                        'bengor.file.infrastructure.persistence.' . $file . '_repository'
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
        return 'bengor.file.application.query.all_' . $file . 's';
    }

    /**
     * {@inheritdoc}
     */
    protected function aliasDefinitionName($file)
    {
        return 'bengor_file.' . $file . '.all_query';
    }
}
