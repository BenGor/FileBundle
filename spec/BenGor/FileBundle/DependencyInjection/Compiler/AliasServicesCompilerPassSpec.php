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

use BenGor\FileBundle\DependencyInjection\Compiler\AliasServicesCompilerPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Spec file of alias services compiler pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class AliasServicesCompilerPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(AliasServicesCompilerPass::class);
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

        $container->setAlias(
            'bengor_file.upload_file',
            'bengor.file.application.service.upload_file_doctrine_transactional'
        )->shouldBeCalled();

        $container->setAlias(
            'bengor_file.overwrite_file',
            'bengor.file.application.service.overwrite_file_doctrine_transactional'
        )->shouldBeCalled();
        $container->setAlias(
            'bengor_file.remove_file',
            'bengor.file.application.service.remove_file_doctrine_transactional'
        )->shouldBeCalled();

        $container->setAlias(
            'bengor_file.file_factory',
            'bengor.file.infrastructure.domain.model.file_factory'
        )->shouldBeCalled();

        $container->setAlias(
            'bengor_file.doctrine_file_repository',
            'bengor.file.infrastructure.persistence.doctrine.file_repository'
        )->shouldBeCalled();

        $this->process($container);
    }
}
