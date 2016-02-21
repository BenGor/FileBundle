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

namespace BenGor\FileBundle;

use BenGor\FileBundle\DependencyInjection\Compiler\AliasServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\ApplicationServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\DomainServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\FilesystemServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\PersistenceServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\TransactionalApplicationServicesCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * BenGor File bundle's kernel class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new FilesystemServicesCompilerPass());
        $container->addCompilerPass(new DomainServicesCompilerPass());
        $container->addCompilerPass(new PersistenceServicesCompilerPass());
        $container->addCompilerPass(new ApplicationServicesCompilerPass());
        $container->addCompilerPass(new TransactionalApplicationServicesCompilerPass());
        $container->addCompilerPass(new AliasServicesCompilerPass());

        $container->loadFromExtension('doctrine', [
            'orm' => [
                'mappings' => [
                    'BenGorFileBundle' => [
                        'type'      => 'yml',
                        'is_bundle' => true,
                        'prefix'    => 'BenGor\\File\\Domain\\Model',
                    ],
                ],
            ],
        ]);
    }
}
