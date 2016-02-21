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

namespace spec\BenGor\FileBundle\DependencyInjection\Compiler;

use BenGor\FileBundle\DependencyInjection\Compiler\ApplicationServicesCompilerPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Spec file of application services compiler pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApplicationServicesCompilerPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ApplicationServicesCompilerPass::class);
    }

    function it_implmements_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_processes(ContainerBuilder $container)
    {
        $container->getParameter('bengor_file.config')->shouldBeCalled()->willReturn([
            'file_class' => [
                'file' => [
                    'class'       => 'BenGor\File\Domain\Model\File',
                    'persistence' => 'doctrine',
                    'filesystem'  => [
                        'gaufrette' => 'file_filesystem',
                        'symfony'   => null,
                    ],
                ],
            ],
        ]);

        $container->getDefinition('bengor.file.infrastructure.filesystem.gaufrette.file')->shouldBeCalled();
        $container->getDefinition('bengor.file.infrastructure.persistence.doctrine.file_repository')->shouldBeCalled();
        $container->getDefinition('bengor.file.infrastructure.domain.model.file_factory')->shouldBeCalled();

        $container->setDefinition(
            'bengor.file.application.service.upload_file',
            Argument::type(Definition::class)
        )->shouldBeCalled();
        $container->setDefinition(
            'bengor.file.application.service.overwrite_file',
            Argument::type(Definition::class)
        )->shouldBeCalled();
        $container->setDefinition(
            'bengor.file.application.service.remove_file',
            Argument::type(Definition::class)
        )->shouldBeCalled();

        $this->process($container);
    }
}
