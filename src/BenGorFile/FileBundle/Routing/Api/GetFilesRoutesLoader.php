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

namespace BenGorFile\FileBundle\Routing\Api;

use BenGorFile\FileBundle\Routing\RoutesLoader;
use Symfony\Component\Routing\Route;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class GetFilesRoutesLoader extends RoutesLoader
{
    public function supports($resource, $type = null)
    {
        return 'bengor_file_get_files_api' === $type;
    }

    protected function register($file, array $config)
    {
        $this->routes->add(
            $config['api_name'],
            new Route(
                $config['api_path'],
                [
                    '_controller' => 'BenGorFileBundle:Api\GetFiles:byIds',
                    'fileClass'   => $file,
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
