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

namespace BenGorFile\FileBundle;

use BenGorFile\FileBundle\DependencyInjection\Compiler\ApplicationCommandsPass;
use BenGorFile\FileBundle\DependencyInjection\Compiler\DomainServicesPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * BenGor File bundle's kernel class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DomainServicesPass());
        $container->addCompilerPass(new ApplicationCommandsPass());

        $this->buildLoadableBundles($container);
    }

    /**
     * Executes the load method of LoadableBundle instances.
     *
     * @param ContainerBuilder $container The container builder
     */
    protected function buildLoadableBundles(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        foreach ($bundles as $bundle) {
            $reflectionClass = new \ReflectionClass($bundle);
            if ($reflectionClass->implementsInterface(LoadableBundle::class)) {
                (new $bundle())->load($container);
            }
        }
    }
}
