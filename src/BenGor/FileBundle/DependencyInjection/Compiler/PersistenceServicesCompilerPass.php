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

use BenGor\File\Infrastructure\Persistence\Doctrine\DoctrineFileRepository;
use BenGor\File\Infrastructure\Persistence\Sql\SqlFileRepository;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Register persistence services compiler pass.
 *
 * Service declaration via PHP allows
 * more flexibility with easy customization.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class PersistenceServicesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');

        foreach ($config['file_class'] as $key => $file) {
            $method = sprintf('load%sRepository', ucfirst($file['persistence']));
            $this->$method($container, $key, $file);
        }
    }

    /**
     * Loads the Doctrine repository.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $key       The name of file type
     * @param array            $file      File configuration tree
     */
    private function loadDoctrineRepository(ContainerBuilder $container, $key, $file)
    {
        $container->setDefinition(
            'bengor.file.infrastructure.persistence.doctrine.' . $key . '_repository',
            (new Definition(
                DoctrineFileRepository::class, [
                    $file['class'],
                ]
            ))->setFactory([
                new Reference('doctrine.orm.default_entity_manager'), 'getRepository',
            ])->setPublic(false)
        );
    }

    /**
     * Loads the SQL repository.
     *
     * @param ContainerBuilder $container The container builder
     * @param string           $key       The name of file type
     * @param array            $file      File configuration tree
     */
    private function loadSqlRepository(ContainerBuilder $container, $key, $file)
    {
        $container->setDefinition(
            'pdo',
            (new Definition(
                \PDO::class, [
                    'mysql:dbname=' . $container->getParameter('database_name'),
                    $container->getParameter('database_user'),
                    $container->getParameter('database_password'),
                ]
            ))->setPublic(false)
        );
        $container->setDefinition(
            'bengor.file.infrastructure.persistence.sql.' . $key . '_repository',
            (new Definition(
                SqlFileRepository::class, [
                    new Reference('pdo'),
                ]
            ))->setPublic(false)
        );
    }
}
