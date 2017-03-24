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

namespace spec\BenGorFile\FileBundle\DependencyInjection\Compiler;

use BenGorFile\File\Domain\Model\File;
use BenGorFile\FileBundle\DependencyInjection\Compiler\ApplicationQueriesPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Spec file of ApplicationQueriesPass pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApplicationQueriesPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ApplicationQueriesPass::class);
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
                    'class'              => 'BenGor\\File\\Domain\\Model\\File',
                    'persistence'        => 'doctrine_orm',
                    'data_transformer'   => 'BenGorFile\\File\\Application\\DataTransformer\\FileDTODataTransformer',
                    'storage'            => 'gaufrette',
                    'upload_destination' => 'images',
                    'download_base_url'         => '/images',
                ],
            ],
        ]);

        $container->getDefinition('bengor.file.infrastructure.persistence.file_repository')
            ->shouldBeCalled()->willReturn($definition);
        $container->getDefinition('bengor.file.infrastructure.persistence.file_specification_factory')
            ->shouldBeCalled()->willReturn($definition);
        $container->getDefinition('bengor.file.application.data_transformer.file_dto')
            ->shouldBeCalled()->willReturn($definition);

        $container->setDefinition(
            'bengor.file.application.query.file_of_id',
            Argument::type(Definition::class)
        )->shouldBeCalled()->willReturn($definition);
        $container->setAlias(
            'bengor_file.file.by_id_query',
            'bengor.file.application.query.file_of_id'
        )->shouldBeCalled()->willReturn($container);

        $container->setDefinition(
            'bengor.file.application.query.file_of_name',
            Argument::type(Definition::class)
        )->shouldBeCalled()->willReturn($definition);
        $container->setAlias(
            'bengor_file.file.by_name_query',
            'bengor.file.application.query.file_of_name'
        )->shouldBeCalled()->willReturn($container);

        $this->process($container);
    }
}
