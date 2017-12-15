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

use BenGorFile\File\Application\Query\FileOfIdQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class GetFileController extends Controller
{
    public function byIdAction($id, $fileClass)
    {
        $file = $this->get('bengor_file.' . $fileClass . '.by_id_query')->__invoke(
            new FileOfIdQuery($id)
        );

        if (!$file) {
            return new JsonResponse([
                'error' => 'Does not exist any file with the given "%s" id',
            ], 404);
        }

        return new JsonResponse($file);
    }
}
