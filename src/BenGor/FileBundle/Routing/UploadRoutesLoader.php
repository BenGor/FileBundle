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

namespace BenGor\FileBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Upload routes loader class.
 *
 * Service that loads dynamically routes of upload.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UploadRoutesLoader implements LoaderInterface
{
    /**
     * Boolean that checks if the routes are already loaded or not.
     *
     * @var bool
     */
    private $loaded;

    /**
     * Array which contains the file classes.
     *
     * @var array
     */
    private $files;

    /**
     * Constructor.
     *
     * @param array $files Array which contains the files
     */
    public function __construct(array $files)
    {
        $this->files = $files;
        $this->loaded = false;
    }

    /**
     * {@inheritdoc}
     */
    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $routes = new RouteCollection();
        foreach ($this->files as $fileCLass => $file) {
            if (!array_key_exists('route_path', $file) || !array_key_exists('route_name', $file)) {
                continue;
            }
            $routes->add($file['route_name'], new Route(
                $file['route_path'],
                [
                    '_controller' => 'BenGorFileBundle:Upload:upload',
                    'fileClass'   => $fileCLass,
                ],
                [],
                [],
                '',
                [],
                ['POST']
            ));
        }
        $this->loaded = true;

        return $routes;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'bengor_file_upload' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function getResolver()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function setResolver(LoaderResolverInterface $resolver)
    {
    }
}
