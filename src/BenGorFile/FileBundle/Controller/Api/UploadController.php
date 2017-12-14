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

namespace BenGorFile\FileBundle\Controller\Api;

use BenGorFile\FileBundle\Controller\UploadAction;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UploadController extends Controller
{
    use UploadAction;

    public function uploadAction(Request $request, $fileClass)
    {
        return new JsonResponse(
            $this->upload(
                $request,
                $this->get('bengor_file.' . $fileClass . '.command_bus'),
                $fileClass
            )
        );
    }
}
