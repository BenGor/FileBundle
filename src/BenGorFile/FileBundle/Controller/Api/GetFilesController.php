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

use BenGorFile\File\Application\Query\ListFilesOfIdsQuery;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class GetFilesController extends Controller
{
    public function byIdsAction(Request $request, $fileClass)
    {
        $ids = $request->get('ids');
        if (!$ids) {
            return new JsonResponse(['error' => 'The "ids" parameter not found'], 400);
        }

        $ids = explode(',', $ids);

        return new JsonResponse(
            $this->get(
                'bengor.file.application.query.list_' . $fileClass . 'files_of_ids'
            )->__invoke(new ListFilesOfIdsQuery($ids))
        );
    }
}
