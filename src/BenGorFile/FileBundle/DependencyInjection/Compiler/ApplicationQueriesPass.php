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

use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Query\CountFilesQueryBuilder;
use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Query\FileOfIdQueryBuilder;
use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Query\FileOfNameQueryBuilder;
use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Query\FilterFilesQueryBuilder;
use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Query\ListFilesOfIdsQueryBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register application queries compiler pass.
 *
 * Query declaration via PHP allows more
 * flexibility with customization extend files.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApplicationQueriesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');

        foreach ($config['file_class'] as $key => $file) {
            (new CountFilesQueryBuilder($container))->build($key);
            (new FilterFilesQueryBuilder($container))->build($key);
            (new FileOfIdQueryBuilder($container))->build($key);
            (new FileOfNameQueryBuilder($container))->build($key);
            (new ListFilesOfIdsQueryBuilder($container))->build($key);
        }
    }
}
