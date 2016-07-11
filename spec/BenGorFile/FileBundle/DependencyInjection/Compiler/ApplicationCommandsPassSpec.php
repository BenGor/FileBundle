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

use BenGorFile\FileBundle\DependencyInjection\Compiler\ApplicationCommandsPass;
use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Spec file of ApplicationCommandsPass class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class ApplicationCommandsPassSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ApplicationCommandsPass::class);
    }

    function it_implements_compiler_pass_interface()
    {
        $this->shouldImplement(CompilerPassInterface::class);
    }
}
