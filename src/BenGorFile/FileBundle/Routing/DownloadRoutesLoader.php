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

namespace BenGorFile\FileBundle\Routing;

use Symfony\Component\Routing\Route;

/**
 * Download routes loader.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DownloadRoutesLoader extends RoutesLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'bengor_file_download' === $type;
    }

    /**
     * {@inheritdoc}
     */
    protected function register($file, array $config)
    {
        $this->routes->add(
            'bengor_file_' . $file . '_download',
            new Route(
                $config['upload_dir'] . '/{filename}',
                [
                    '_controller'       => 'BenGorFileBundle:Download:' . $config['storage'],
                    'uploadDestination' => $config['upload_destination'],
                ],
                [],
                [],
                '',
                [],
                ['GET']
            )
        );
    }
}
