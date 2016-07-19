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

use BenGorFile\File\Application\Command\Upload\UploadFileCommand;
use BenGorFile\File\Infrastructure\CommandBus\FileCommandBus;
use Symfony\Component\HttpFoundation\Request;

/**
 * BenGor file's upload action trait.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
trait UploadAction
{
    /**
     * Upload action.
     *
     * @param Request        $aRequest    The request
     * @param FileCommandBus $aCommandBus The command bus
     * @param string         $aProperty   The file property that want to get from request
     *
     * @return array
     */
    public function uploadAction(Request $aRequest, FileCommandBus $aCommandBus, $aProperty)
    {
        $uploadedFile = $aRequest->files->get($aProperty);
        $command = new UploadFileCommand(
            $uploadedFile->getClientOriginalName(),
            file_get_contents($uploadedFile->getPathname()),
            $uploadedFile->getMimeType()
        );
        $aCommandBus->handle($command);

        $aRequest->files->remove($aProperty);

        return [
            'id'        => $command->id(),
            'filename'  => $uploadedFile->getClientOriginalName(),
            'mime_type' => $uploadedFile->getMimeType(),
        ];
    }
}
