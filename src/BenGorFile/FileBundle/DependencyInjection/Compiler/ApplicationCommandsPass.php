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

namespace BenGorFile\FileBundle\DependencyInjection\Compiler;

use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Command\OverwriteFileCommandBuilder;
use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Command\RemoveFileCommandBuilder;
use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Command\RenameFileCommandBuilder;
use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Command\UploadFileCommandBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Register application commands compiler pass.
 *
 * Command declaration via PHP allows more
 * flexibility with customization extend files.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApplicationCommandsPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $config = $container->getParameter('bengor_file.config');

        foreach ($config['file_class'] as $key => $file) {
            (new UploadFileCommandBuilder($container, $file['persistence'], $file))->build($key);
            (new OverwriteFileCommandBuilder($container, $file['persistence']))->build($key);
            (new RenameFileCommandBuilder($container, $file['persistence']))->build($key);
            (new RemoveFileCommandBuilder($container, $file['persistence']))->build($key);
        }
    }
}
