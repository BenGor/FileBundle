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
use BenGorFile\FileBundle\DependencyInjection\Compiler\Routing\GetFilesRoutesLoaderBuilder;
use BenGorFile\FileBundle\DependencyInjection\Compiler\Routing\UploadRoutesLoaderBuilder;
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
        $getFilesConfiguration = [];
        $uploadConfiguration = [];

        foreach ($config['file_class'] as $key => $file) {
            $downloadConfiguration[$key] = [
                'storage'            => $file['storage'],
                'upload_destination' => $file['upload_destination'],
                'download_base_url'  => $file['download_base_url'],
            ];
            $getFilesConfiguration[$key] = array_merge(
                $file['use_cases']['get_files'],
                $file['routes']['get_files']
            );
            $uploadConfiguration[$key] = array_merge(
                $file['use_cases']['upload'],
                $file['routes']['upload']
            );
            $uploadConfiguration[$key]['upload_strategy'] = $file['upload_strategy'];
        }

        (new DownloadRoutesLoaderBuilder($container, $downloadConfiguration))->build();
        (new GetFilesRoutesLoaderBuilder($container, $getFilesConfiguration))->build();
        (new UploadRoutesLoaderBuilder($container, $uploadConfiguration))->build();
    }
}
