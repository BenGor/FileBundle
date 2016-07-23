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

use BenGorFile\FileBundle\BenGorFileBenGorFileBundle;
use BenGorFile\FileBundle\DependencyInjection\Compiler\ApplicationCommandsPass;
use BenGorFile\FileBundle\DependencyInjection\Compiler\ApplicationDataTransformersPass;
use BenGorFile\FileBundle\DependencyInjection\Compiler\ApplicationQueriesPass;
use BenGorFile\FileBundle\DependencyInjection\Compiler\DomainServicesPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Spec file of bengor file bundle class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileBenGorFileBundleSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BenGorFileBenGorFileBundle::class);
    }

    function it_extends_symfony_bundle()
    {
        $this->shouldHaveType(Bundle::class);
    }

    function it_builds(ContainerBuilder $container)
    {
        $container->addCompilerPass(
            Argument::type(DomainServicesPass::class), PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);
        $container->addCompilerPass(
            Argument::type(ApplicationCommandsPass::class), PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);
        $container->addCompilerPass(
            Argument::type(ApplicationDataTransformersPass::class), PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);
        $container->addCompilerPass(
            Argument::type(ApplicationQueriesPass::class), PassConfig::TYPE_OPTIMIZE
        )->shouldBeCalled()->willReturn($container);

        $container->getParameter('kernel.bundles')->shouldBeCalled()->willReturn([
            'FrameworkBundle' => 'Symfony\Bundle\FrameworkBundle\FrameworkBundle',
        ]);

        $this->build($container);
    }
}
