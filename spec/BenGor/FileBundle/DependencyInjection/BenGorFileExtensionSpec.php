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

namespace spec\BenGor\FileBundle\DependencyInjection;

use BenGor\FileBundle\DependencyInjection\BenGorFileExtension;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * Spec file of bengor file extension class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class BenGorFileExtensionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(BenGorFileExtension::class);
    }

    function it_extends_symfony_extension()
    {
        $this->shouldHaveType(Extension::class);
    }

    function it_does_not_loads_because_required_configuration_is_missing(ContainerBuilder $container)
    {
        $this->load([], $container);
    }
}
