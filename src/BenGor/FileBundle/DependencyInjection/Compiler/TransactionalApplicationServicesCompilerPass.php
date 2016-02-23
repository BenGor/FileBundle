<?php

/*
 * This file is part of the BenGorFileBundle bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGor\FileBundle\DependencyInjection\Compiler;

use BenGor\File\Infrastructure\Application\Service\SqlSession;
use Ddd\Application\Service\TransactionalApplicationService;
use Ddd\Infrastructure\Application\Service\DoctrineSession;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register application transactional services compiler pass.
 *
 * The services are decorate with transactional
 * application service to simplify the use of them.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TransactionalApplicationServicesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');

        foreach ($config['file_class'] as $key => $file) {
            $method = sprintf('load%sSession', ucfirst($file['persistence']));
            $this->$method($container);

            $container->register(
                'bengor.file.application.service.upload_' . $key . '_' . $file['persistence'] . '_transactional',
                TransactionalApplicationService::class
            )->addArgument(
                new Reference('bengor.file.application.service.upload_' . $key)
            )->addArgument(
                new Reference('bengor.file.infrastructure.application.service.' . $file['persistence'] . '_session')
            )->setPublic(false);

            $container->register(
                'bengor.file.application.service.overwrite_' . $key . '_' . $file['persistence'] . '_transactional',
                TransactionalApplicationService::class
            )->addArgument(
                new Reference('bengor.file.application.service.overwrite_' . $key)
            )->addArgument(
                new Reference('bengor.file.infrastructure.application.service.' . $file['persistence'] . '_session')
            )->setPublic(false);

            $container->register(
                'bengor.file.application.service.remove_' . $key . '_' . $file['persistence'] . '_transactional',
                TransactionalApplicationService::class
            )->addArgument(
                new Reference('bengor.file.application.service.remove_' . $key)
            )->addArgument(
                new Reference('bengor.file.infrastructure.application.service.' . $file['persistence'] . '_session')
            )->setPublic(false);
        }
    }

    /**
     * Loads the Doctrine session service.
     *
     * @param ContainerBuilder $container The container
     */
    private function loadDoctrineSession(ContainerBuilder $container)
    {
        $container->register(
            'bengor.file.infrastructure.application.service.doctrine_session',
            DoctrineSession::class
        )->addArgument(
            new Reference('doctrine.orm.default_entity_manager')
        )->setPublic(false);
    }

    /**
     * Loads the SQL session service.
     *
     * @param ContainerBuilder $container The container
     */
    private function loadSqlSession(ContainerBuilder $container)
    {
        $container->register(
            'bengor.file.infrastructure.application.service.sql_session',
            SqlSession::class
        )->addArgument(
            new Reference('pdo')
        )->setPublic(false);
    }
}
