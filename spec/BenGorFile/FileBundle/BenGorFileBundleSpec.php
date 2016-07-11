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

namespace spec\BenGorFile\FileBundle;

use BenGorFile\FileBundle\BenGorFileBundle;
use BenGorFile\FileBundle\DependencyInjection\Compiler\DomainServicesCompilerPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Spec file of bengor file bundle class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BenGorFileBundle::class);
    }

    function it_extends_symfony_bundle()
    {
        $this->shouldHaveType(Bundle::class);
    }

    function it_builds(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            Argument::type(DomainServicesCompilerPass::class)
        )->shouldBeCalled()->willReturn($container);

        $this->build($container);
    }
}
