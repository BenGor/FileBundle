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
use BenGorFile\FileBundle\DependencyInjection\Compiler\ApplicationDataTransformersPass;
use BenGorFile\FileBundle\DependencyInjection\Compiler\ApplicationQueriesPass;
use BenGorFile\FileBundle\DependencyInjection\Compiler\DomainServicesPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * BenGor File bundle's kernel class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileBenGorFileBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DomainServicesPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new ApplicationCommandsPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new ApplicationDataTransformersPass(), PassConfig::TYPE_OPTIMIZE);
        $container->addCompilerPass(new ApplicationQueriesPass(), PassConfig::TYPE_OPTIMIZE);

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
