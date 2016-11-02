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

namespace spec\BenGorFile\FileBundle\Twig;

use BenGorFile\FileBundle\Twig\DownloadExtension;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Spec file of DownloadExtension class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DownloadExtensionSpec extends ObjectBehavior
{
    function let(UrlGeneratorInterface $urlGenerator)
    {
        $this->beConstructedWith($urlGenerator);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DownloadExtension::class);
    }

    function it_extends_Twig_extension()
    {
        $this->shouldHaveType(\Twig_Extension::class);
    }

    function it_download(UrlGeneratorInterface $urlGenerator)
    {
        $urlGenerator->generate(
            'bengor_file_file_download', ['filename' => 'logo.jpg'], UrlGeneratorInterface::ABSOLUTE_URL
        )->shouldBeCalled()->willReturn('/files/logo.jpg');

        $this->download('file', 'logo.jpg')->shouldReturn('/files/logo.jpg');
    }

    function it_gets_name()
    {
        $this->getName()->shouldReturn('bengor_file_download');
    }
}
