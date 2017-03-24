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

namespace spec\BenGorFile\FileBundle\Routing;

use BenGorFile\FileBundle\Routing\DownloadRoutesLoader;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Config\Loader\LoaderInterface;

/**
 * Spec file of DownloadRoutesLoader class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DownloadRoutesLoaderSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith([
            'file' => [
                'storage'            => 'gaufrette',
                'upload_destination' => 'files',
                'download_base_url'         => '/files',
            ],
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DownloadRoutesLoader::class);
    }

    function it_implements_loader_interface()
    {
        $this->shouldHaveType(LoaderInterface::class);
    }

    function it_loads()
    {
        $this->load('resource');
    }

    function it_supports_bengor_user_enable()
    {
        $this->supports('resource', 'bengor_file_download')->shouldReturn(true);
    }
}
