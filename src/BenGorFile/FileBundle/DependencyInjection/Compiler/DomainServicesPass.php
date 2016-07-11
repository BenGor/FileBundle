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

use BenGorFile\File\Infrastructure\Domain\Model\FileFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Register domain model services compiler pass.
 *
 * Service declaration via PHP allows
 * more flexibility with easy customization.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DomainServicesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');
        foreach ($config['file_class'] as $key => $file) {
            $container->setDefinition(
                'bengor.file.infrastructure.domain.model.' . $key . '_factory',
                (new Definition(
                    FileFactory::class, [
                        $file['class'],
                    ]
                ))->setPublic(false)
            );
            $container->setAlias(
                'bengor_file.' . $key . '.factory',
                'bengor.file.infrastructure.domain.model.' . $key . '_factory'
            );
        }
    }
}
