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

namespace BenGorFile\FileBundle\DependencyInjection\Compiler\Application\Command;

use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\ApplicationBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Base command builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
abstract class CommandBuilder implements ApplicationBuilder
{
    /**
     * Configuration array.
     *
     * @var array
     */
    protected $configuration;

    /**
     * The container builder.
     *
     * @var ContainerBuilder
     */
    protected $container;

    /**
     * The persistence driver.
     *
     * @var string
     */
    protected $persistence;

    /**
     * Constructor.
     *
     * @param ContainerBuilder $container     The container builder
     * @param string           $persistence   The persistence driver
     * @param array            $configuration The configuration tree
     */
    public function __construct(ContainerBuilder $container, $persistence, array $configuration = [])
    {
        $this->container = $container;
        $this->persistence = $persistence;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function build($file)
    {
        $this->register($file);

        $this->container->setAlias(
            $this->aliasDefinitionName($file),
            $this->definitionName($file)
        );

        return $this->container;
    }

    /**
     * Gets the command definition name.
     *
     * @param string $file The file name
     *
     * @return string
     */
    abstract protected function definitionName($file);

    /**
     * Gets the command definition name alias.
     *
     * @param string $file The file name
     *
     * @return string
     */
    abstract protected function aliasDefinitionName($file);

    /**
     * Registers the command into container.
     *
     * @param string $file The file name
     */
    abstract protected function register($file);
}
