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

use BenGorFile\FileBundle\DependencyInjection\Compiler\Routing\DownloadRoutesLoaderBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Load routes compiler pass.
 *
 * Based on configuration the routes are created dynamically.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RoutesPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');

        $downloadConfiguration = [];

        foreach ($config['file_class'] as $key => $file) {
            $downloadConfiguration[$key] = [
                'storage'            => $file['storage'],
                'upload_destination' => $file['upload_destination'],
                'download_base_url'  => $file['download_base_url'],
            ];
        }

        (new DownloadRoutesLoaderBuilder($container, $downloadConfiguration))->build();
    }
}
