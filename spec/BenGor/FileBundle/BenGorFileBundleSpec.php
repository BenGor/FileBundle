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

namespace spec\BenGor\FileBundle;

use BenGor\FileBundle\BenGorFileBundle;
use BenGor\FileBundle\DependencyInjection\Compiler\AliasServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\ApplicationServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\DomainServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\FilesystemServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\PersistenceServicesCompilerPass;
use BenGor\FileBundle\DependencyInjection\Compiler\TransactionalApplicationServicesCompilerPass;
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
            Argument::type(FilesystemServicesCompilerPass::class)
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(DomainServicesCompilerPass::class)
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(PersistenceServicesCompilerPass::class)
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(ApplicationServicesCompilerPass::class)
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(TransactionalApplicationServicesCompilerPass::class)
        )->shouldBeCalled()->willReturn($container);

        $container->addCompilerPass(
            Argument::type(AliasServicesCompilerPass::class)
        )->shouldBeCalled()->willReturn($container);

        $container->loadFromExtension('doctrine', [
            'orm' => [
                'mappings' => [
                    'BenGorFileBundle' => [
                        'type'      => 'yml',
                        'is_bundle' => true,
                        'prefix'    => 'BenGor\\File\\Domain\\Model',
                    ],
                ],
            ],
        ])->shouldBeCalled()->willReturn($container);

        $this->build($container);
    }
}
