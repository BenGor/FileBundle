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

namespace BenGor\FileBundle\Controller;

use BenGor\File\Application\Service\UploadFileRequest;
use BenGor\FileBundle\Form\Type\FileType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * BenGor file's upload controller.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UploadController extends Controller
{
    /**
     * Upload AJAX action.
     *
     * @param Request $request   The request
     * @param string  $fileClass Extra parameter that contains the file class
     *
     * @return JsonResponse
     */
    public function uploadAction(Request $request, $fileClass)
    {
        if (false === $request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(FileType::class, null, ['request' => UploadFileRequest::class]);
        $form->handleRequest($request);
        if (true === $form->isValid()) {
            try {
                $response = $this->get('bengor_file.upload_' . $fileClass)->execute($form->getData());

                return new JsonResponse(['fileId' => $response->file()->id()->id()]);
            } catch (\Exception $exception) {
                return new JsonResponse($exception->getMessage(), 400);
            }
        }

        return new JsonResponse($this->getFormErrors($form), 400);
    }

    /**
     * Returns all the errors from form into array.
     *
     * @param FormInterface $form The form
     *
     * @return array
     */
    protected function getFormErrors(FormInterface $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->getFormErrors($child);
            }
        }

        return $errors;
    }
}
