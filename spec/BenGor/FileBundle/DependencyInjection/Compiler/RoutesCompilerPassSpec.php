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

use BenGor\FileBundle\DependencyInjection\Compiler\RoutesCompilerPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Spec file of routes services compiler pass.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class RoutesCompilerPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RoutesCompilerPass::class);
    }

    function it_implmements_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }

    function it_processes(ContainerBuilder $container, Definition $definition)
    {
        $container->getParameter('bengor_file.config')->shouldBeCalled()->willReturn([
            'file_class' => [
                'file'  => [
                    'class'       => 'BenGor\File\Domain\Model\File',
                    'persistence' => 'doctrine',
                    'filesystem'  => [
                        'gaufrette' => 'file_filesystem',
                        'symfony'   => null,
                    ],
                    'routes'      => [
                        'upload' => [
                            'enabled' => true,
                            'name'    => 'bengor_file_file_upload',
                            'path'    => '/bengor-file/file/upload',
                        ],
                    ],
                ],
                'image' => [
                    'class'       => 'BenGor\File\Domain\Model\File',
                    'persistence' => 'sql',
                    'filesystem'  => [
                        'gaufrette' => 'image_filesystem',
                        'symfony'   => null,
                    ],
                    'routes'      => [
                        'upload' => [
                            'enabled' => false,
                            'name'    => 'bengor_file_image_upload',
                            'path'    => '/bengor-file/image/upload',
                        ],
                    ],
                ],
            ],
        ]);

        $container->hasDefinition('bengor.file_bundle.routing.upload_routes_loader')
            ->shouldBeCalled()->willReturn(true);
        $container->getDefinition('bengor.file_bundle.routing.upload_routes_loader')
            ->shouldBeCalled()->willReturn($definition);

        $this->process($container);
    }
}
