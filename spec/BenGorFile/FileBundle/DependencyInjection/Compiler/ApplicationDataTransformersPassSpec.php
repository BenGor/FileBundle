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
use BenGorFile\FileBundle\DependencyInjection\Compiler\ApplicationDataTransformersPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Spec file of ApplicationDataTransformersPass pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApplicationDataTransformersPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ApplicationDataTransformersPass::class);
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

        $container->setDefinition(
            'bengor.file.application.data_transformer.file_dto',
            Argument::type(Definition::class)
        )->shouldBeCalled()->willReturn($definition);
        $container->setAlias(
            'bengor_file.file.dto_data_transformer',
            'bengor.file.application.data_transformer.file_dto'
        )->shouldBeCalled()->willReturn($container);

        $this->process($container);
    }
}
