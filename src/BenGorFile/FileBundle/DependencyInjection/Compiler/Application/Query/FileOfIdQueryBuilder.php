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

use BenGorFile\File\Application\Query\FileOfIdHandler;
use Symfony\Component\DependencyInjection\Definition;

/**
 * File of id query builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileOfIdQueryBuilder extends QueryBuilder
{
    /**
     * {@inheritdoc}
     */
    public function register($file)
    {
        $this->container->setDefinition(
            $this->definitionName($file),
            new Definition(
                FileOfIdHandler::class, [
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
        return 'bengor.file.application.query.' . $file . '_of_id';
    }

    /**
     * {@inheritdoc}
     */
    protected function aliasDefinitionName($file)
    {
        return 'bengor_file.' . $file . '.by_id_query';
    }
}
