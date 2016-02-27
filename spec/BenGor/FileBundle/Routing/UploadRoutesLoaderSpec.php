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

namespace spec\BenGor\FileBundle\Routing;

use BenGor\FileBundle\Routing\UploadRoutesLoader;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Spec file of security routes loader class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UploadRoutesLoaderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'file' => [
                'route_name' => 'bengor_file_file_upload',
                'route_path' => '/bengor-file/file/upload',
            ],
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UploadRoutesLoader::class);
    }

    function it_implements_loader_interface()
    {
        $this->shouldHaveType(LoaderInterface::class);
    }

    function it_loads()
    {
        $this->load('resource');
    }
}
