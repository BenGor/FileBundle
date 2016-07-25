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

use BenGorFile\FileBundle\DependencyInjection\Compiler\TwigPass;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Spec file of TwigPass class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class TwigPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TwigPass::class);
    }

    function it_implements_compiler_pass_interface()
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
                    'upload_dir'         => '/images',
                ],
            ],
        ]);

        $container->getDefinition('router')->shouldBeCalled()->willReturn($definition);
        $container->setDefinition(
            'bengor_file.file_bundle.twig.view_extension_file',
            Argument::type(Definition::class)
        )->shouldBeCalled();

        $this->process($container);
    }
}
