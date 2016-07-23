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

namespace BenGorFile\FileBundle\Controller;

use BenGorFile\File\Application\Command\Remove\RemoveFileCommand;
use BenGorFile\File\Infrastructure\CommandBus\FileCommandBus;

/**
 * BenGor file's remove action trait.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
trait RemoveAction
{
    /**
     * Overwrite action.
     *
     * @param string         $anId        The file id
     * @param FileCommandBus $aCommandBus The command bus
     */
    public function removeAction($anId, FileCommandBus $aCommandBus)
    {
        $aCommandBus->handle(
            new RemoveFileCommand($anId)
        );
    }
}
