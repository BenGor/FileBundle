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

namespace spec\BenGorFile\FileBundle\Controller;

use BenGorFile\FileBundle\Controller\DownloadController;
use PhpSpec\ObjectBehavior;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Spec file of DownloadController class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DownloadControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(DownloadController::class);
    }

    function it_extends_controller()
    {
        $this->shouldHaveType(Controller::class);
    }
}
