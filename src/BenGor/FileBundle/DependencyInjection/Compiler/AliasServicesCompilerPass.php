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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Alias services compiler pass.
 *
 * In order to simplify the long names of most used
 * services, this class adds more readable and
 * concise aliases for this kind of services.
 *
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class AliasServicesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');

        $aliasMap = [];

        foreach ($config['file_class'] as $key => $file) {
            $aliasMap = array_merge([
                'bengor_file.upload_' . $key    => 'bengor.file.application.service.upload_' . $key . '_' . $file['persistence'] . '_transactional',
                'bengor_file.overwrite_' . $key => 'bengor.file.application.service.overwrite_' . $key . '_' . $file['persistence'] . '_transactional',
                'bengor_file.remove_' . $key    => 'bengor.file.application.service.remove_' . $key . '_' . $file['persistence'] . '_transactional',

                'bengor_file.' . $key . '_factory' => 'bengor.file.infrastructure.domain.model.' . $key . '_factory',

                'bengor_file.' . $file['persistence'] . '_' . $key . '_repository' => 'bengor.file.infrastructure.persistence.' . $file['persistence'] . '.' . $key . '_repository',
            ], $aliasMap);
        }
        foreach ($aliasMap as $alias => $id) {
            $container->setAlias($alias, $id);
        }
    }
}
