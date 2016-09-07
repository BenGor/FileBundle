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

namespace BenGorFile\FileBundle\DependencyInjection\Compiler\Application\DataTransformer;

use BenGorFile\FileBundle\DependencyInjection\Compiler\Application\ApplicationBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * File DTO data transformer builder.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileDTODataTransformerBuilder implements ApplicationBuilder
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
     * The FQCN or the service id of file data transformer.
     *
     * @var string
     */
    protected $dataTransformer;

    /**
     * Constructor.
     *
     * @param ContainerBuilder $container       The container builder
     * @param string           $dataTransformer The FQCN or the service id of file data transformer
     * @param array            $configuration   The configuration tree
     */
    public function __construct(ContainerBuilder $container, $dataTransformer, array $configuration = [])
    {
        $this->container = $container;
        $this->dataTransformer = $dataTransformer;
        $this->configuration = $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function build($file)
    {
        $dataTransformer = class_exists($this->dataTransformer)
            ? new Definition($this->dataTransformer)
            : new Reference($this->dataTransformer);

        $this->container->setDefinition(
            'bengor.file.application.data_transformer.' . $file . '_dto',
            $dataTransformer
        );

        $this->container->setAlias(
            'bengor_file.' . $file . '.dto_data_transformer',
            'bengor.file.application.data_transformer.' . $file . '_dto'
        );

        return $this->container;
    }
}
