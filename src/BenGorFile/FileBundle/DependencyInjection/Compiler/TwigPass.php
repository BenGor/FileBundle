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

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register Twig services compiler pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TwigPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bengor_file.file_bundle.twig.download_extension')) {
            return;
        }

        $config = $container->getParameter('bengor_file.config');

        $handlers = [];
        foreach ($config['file_class'] as $key => $file) {
            $handlers[$key] = $container->getDefinition('bengor.file.application.query.' . $key . '_of_id');
        }

        $container->getDefinition('bengor_file.file_bundle.twig.download_extension')->replaceArgument(
            1,
            $handlers
        );
    }
}
