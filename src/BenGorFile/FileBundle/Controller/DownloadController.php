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

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * File download controller.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class DownloadController extends Controller
{
    /**
     * Gets the file of given file name in the Gaufrette filesystem.
     *
     * @param string $filename          The file name
     * @param string $uploadDestination The gaufrette filesystem
     *
     * @return BinaryFileResponse
     */
    public function gaufretteAction($filename, $uploadDestination)
    {
        return new BinaryFileResponse('gaufrette://' . $uploadDestination . '/' . $filename);
    }

    /**
     * Gets the file of given file name in the Symfony filesystem.
     *
     * @param string $filename          The file name
     * @param string $uploadDestination The path of file storage
     *
     * @return BinaryFileResponse
     */
    public function symfonyAction($filename, $uploadDestination)
    {
        return new BinaryFileResponse($uploadDestination . '/' . $filename);
    }
}
