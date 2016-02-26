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
use BenGor\File\Domain\Model\UploadedFileException;
use BenGor\FileBundle\Form\Type\FileType;
use BenGor\FileBundle\Form\Type\UserType;
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
     * Upload action.
     *
     * @param Request $request The request
     *
     * @return JsonResponse
     */
    public function nonAjaxAction(Request $request)
    {
        $form = $this->createForm(UserType::class);
        if (true === $request->isMethod('POST')) {
            $form->handleRequest($request);
            if (true === $form->isValid()) {
                try {
                    $manager = $this->getDoctrine()->getManager();
                    $manager->persist($form->getData());
                    $manager->flush();
                    $this->addFlash('notice', 'The upload process is successfully done');
                } catch (UploadedFileException $exception) {
                    $this->addFlash('error', $exception->getMessage());
                } catch (\Exception $exception) {
                    $this->addFlash('error', $exception->getMessage());
                }
            }
        }

        return $this->render('@BenGorFile/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Upload AJAX action.
     *
     * @param Request $request The request
     *
     * @return JsonResponse
     */
    public function ajaxAction(Request $request)
    {
        if (false === $request->isXmlHttpRequest()) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(FileType::class, null, ['request' => UploadFileRequest::class]);
        $form->handleRequest($request);
        if (true === $form->isValid()) {
            try {
                $response = $this->get('bengor_file.upload_image')->execute($form->getData());

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
