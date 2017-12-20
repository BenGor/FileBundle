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

use BenGorFile\File\Infrastructure\Persistence\Sql\SqlFileRepository;
use BenGorFile\File\Infrastructure\Persistence\Sql\SqlFileSpecificationFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register Sql services compiler pass.
 *
 * Service declaration via PHP allows more
 * flexibility with customization extend files.
 *
 * @author Mikel Etxebarria <mikeletxe4594@gmail.com>
 */
class SqlServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');
        foreach ($config['file_class'] as $key => $file) {
            if ('sql' !== $file['persistence']) {
                continue;
            }

            if (!$container->hasDefinition('bengor.file.infrastructure.persistence.pdo')) {
                $container->setDefinition(
                    'bengor.file.infrastructure.persistence.pdo',
                    (new Definition(
                        \PDO::class, [
                            "mysql:host=%database_host%;dbname=%database_name%",
                            "%database_user%",
                            "%database_password%",
                            [],
                        ]
                    ))->setPublic(false)
                );
            }

            $container->setDefinition(
                'bengor.file.infrastructure.persistence.' . $key . '_repository',
                (new Definition(
                    SqlFileRepository::class, [
                        new Reference('bengor.file.infrastructure.persistence.pdo'),
                    ]
                ))->setPublic(false)
            );

            $container->setAlias(
                'bengor_file.' . $key . '.repository',
                'bengor.file.infrastructure.persistence.' . $key . '_repository'
            );

            $container->setDefinition(
                'bengor.file.infrastructure.persistence.' . $key . '_specification_factory',
                (new Definition(
                    SqlFileSpecificationFactory::class
                ))->setPublic(false)
            );

            $container->setAlias(
                'bengor_file.' . $key . '.specification_factory',
                'bengor.file.infrastructure.persistence.' . $key . '_specification_factory'
            );
        }
    }
}

