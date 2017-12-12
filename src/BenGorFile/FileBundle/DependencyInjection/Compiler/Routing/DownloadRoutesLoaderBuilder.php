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

namespace BenGorFile\FileBundle\DependencyInjection\Compiler\Routing;

/**
 * Download routes loader builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DownloadRoutesLoaderBuilder extends RoutesLoaderBuilder
{
    /**
     * {@inheritdoc}
     */
    protected function sanitize(array $configuration)
    {
        foreach ($configuration as $key => $config) {
            if (null === $config['download_base_url']) {
                $configuration[$key]['download_base_url'] = $this->defaultUploadDir($key);
            }
        }

        return $configuration;
    }

    /**
     * {@inheritdoc}
     */
    protected function definitionName()
    {
        return 'bengor.file_bundle.routing.download_routes_loader';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultUploadDir($file)
    {
        return sprintf('/%ss', $file);
    }
}
