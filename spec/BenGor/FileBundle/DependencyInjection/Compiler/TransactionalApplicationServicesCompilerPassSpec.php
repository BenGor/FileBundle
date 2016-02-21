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

use BenGor\FileBundle\DependencyInjection\Compiler\TransactionalApplicationServicesCompilerPass;
use Ddd\Application\Service\TransactionalApplicationService;
use Ddd\Infrastructure\Application\Service\DoctrineSession;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Spec file of transactional application services compiler pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TransactionalApplicationServicesCompilerPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TransactionalApplicationServicesCompilerPass::class);
    }

    function it_implmements_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_processes(ContainerBuilder $container, Definition $definition)
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

        $container->register(
            'bengor.file.infrastructure.application.service.doctrine_session',
            DoctrineSession::class
        )->shouldBeCalled()->willReturn($definition);
        $definition->addArgument(Argument::type(Reference::class))->shouldBeCalled()->willReturn($definition);
        $definition->setPublic(false)->shouldBeCalled()->willReturn($definition);

        $container->register(
            'bengor.file.application.service.upload_file_doctrine_transactional',
            TransactionalApplicationService::class
        )->shouldBeCalled()->willReturn($definition);
        $definition->addArgument(Argument::type(Reference::class))->shouldBeCalled()->willReturn($definition);
        $definition->setPublic(false)->shouldBeCalled()->willReturn($definition);

        $container->register(
            'bengor.file.application.service.overwrite_file_doctrine_transactional',
            TransactionalApplicationService::class
        )->shouldBeCalled()->willReturn($definition);
        $definition->addArgument(Argument::type(Reference::class))->shouldBeCalled()->willReturn($definition);
        $definition->setPublic(false)->shouldBeCalled()->willReturn($definition);

        $container->register(
            'bengor.file.application.service.remove_file_doctrine_transactional',
            TransactionalApplicationService::class
        )->shouldBeCalled()->willReturn($definition);
        $definition->addArgument(Argument::type(Reference::class))->shouldBeCalled()->willReturn($definition);
        $definition->setPublic(false)->shouldBeCalled()->willReturn($definition);

        $this->process($container);
    }
}
