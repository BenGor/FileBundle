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
 * Load routes compiler pass.
 *
 * Based on configuration the routes are created dynamically.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RoutesCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->processUploadRoutes($container);
    }

    /**
     * Process the upload routes.
     *
     * @param ContainerBuilder $container The container
     */
    private function processUploadRoutes(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('bengor.file_bundle.routing.upload_routes_loader')) {
            return;
        }
        $config = $container->getParameter('bengor_file.config');
        $files = [];
        foreach ($config['file_class'] as $key => $file) {
            if (false === $file['routes']['upload']['enabled']) {
                continue;
            }
            $files[$key]['route_name'] = $file['routes']['upload']['name'] ?: 'bengor_file_' . $key . '_upload';
            $files[$key]['route_path'] = $file['routes']['upload']['path'] ?: '/bengor-file/' . $key . '/upload';
        }
        $container->getDefinition(
            'bengor.file_bundle.routing.upload_routes_loader'
        )->replaceArgument(0, array_unique($files, SORT_REGULAR));
    }
}
