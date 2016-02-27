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

namespace spec\BenGor\FileBundle\Controller;

use BenGor\File\Application\Service\UploadFileRequest;
use BenGor\File\Application\Service\UploadFileService;
use BenGor\File\Infrastructure\Domain\Model\FileFactory;
use BenGor\File\Infrastructure\Filesystem\InMemory\InMemoryFilesystem;
use BenGor\File\Infrastructure\Persistence\InMemory\InMemoryFileRepository;
use BenGor\File\Infrastructure\UploadedFile\Test\DummyUploadedFile;
use BenGor\FileBundle\Controller\UploadController;
use BenGor\FileBundle\Form\Type\FileType;
use Ddd\Application\Service\TransactionalApplicationService;
use Ddd\Infrastructure\Application\Service\DummySession;
use PhpSpec\ObjectBehavior;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Spec file of upload controller class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class UploadControllerSpec extends ObjectBehavior
{
    function let(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UploadController::class);
    }

    function it_extends_controller()
    {
        $this->shouldHaveType(Controller::class);
    }

    function it_does_not_upload_because_is_not_xml_http_request(Request $request)
    {
        $request->isXmlHttpRequest()->shouldBeCalled()->willReturn(false);

        $this->shouldThrow(NotFoundHttpException::class)->duringUploadAction($request, 'file');
    }

    function it_does_not_upload_because_form_is_invalid(
        ContainerInterface $container,
        Request $request,
        FormFactoryInterface $formFactory,
        FormInterface $form,
        FormErrorIterator $formErrors
    ) {
        $request->isXmlHttpRequest()->shouldBeCalled()->willReturn(true);
        $container->get('form.factory')->shouldBeCalled()->willReturn($formFactory);
        $formFactory->create(FileType::class, null, ['request' => UploadFileRequest::class])
            ->shouldBeCalled()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willReturn(false);

        $form->getErrors()->shouldBeCalled()->willReturn($formErrors);
        $form->all()->shouldBeCalled()->willReturn([]);

        $this->uploadAction($request, 'file')->shouldReturnAnInstanceOf(JsonResponse::class);
    }

    function it_uploads_action(
        ContainerInterface $container,
        Request $request,
        FormFactoryInterface $formFactory,
        FormInterface $form
    ) {
        $serviceRequest = new UploadFileRequest(
            new DummyUploadedFile('test-content', 'original-name', 'pdf'),
            'dummy-file-name'
        );
        $service = new TransactionalApplicationService(
            new UploadFileService(
                new InMemoryFilesystem(),
                new InMemoryFileRepository(),
                new FileFactory()
            ), new DummySession()
        );
        $request->isXmlHttpRequest()->shouldBeCalled()->willReturn(true);
        $container->get('form.factory')->shouldBeCalled()->willReturn($formFactory);
        $formFactory->create(FileType::class, null, ['request' => UploadFileRequest::class])
            ->shouldBeCalled()->willReturn($form);
        $form->handleRequest($request)->shouldBeCalled()->willReturn($form);
        $form->isValid()->shouldBeCalled()->willReturn(true);
        $container->get('bengor_file.upload_file')->shouldBeCalled()->willReturn($service);
        $form->getData()->shouldBeCalled()->willReturn($serviceRequest);

        $this->uploadAction($request, 'file')->shouldReturnAnInstanceOf(JsonResponse::class);
    }
}
