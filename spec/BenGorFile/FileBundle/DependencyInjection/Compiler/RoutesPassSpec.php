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

use BenGorFile\FileBundle\DependencyInjection\Compiler\RoutesPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Spec file of RoutesPass class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RoutesPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RoutesPass::class);
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
        $container->hasDefinition('bengor.file_bundle.routing.download_routes_loader')
            ->shouldBeCalled()->willReturn(true);
        $container->getDefinition('bengor.file_bundle.routing.download_routes_loader')
            ->shouldBeCalled()->willReturn($definition);

        $this->process($container);
    }
}
