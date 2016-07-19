#Updating existing use case

```php
<?php

namespace AppBundle\Entity;

use BenGorFile\File\Domain\Model\File as BaseFile;
use BenGorFile\File\Domain\Model\FileId;
use BenGorFile\File\Domain\Model\FileMimeType;
use BenGorFile\File\Domain\Model\FileName;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bengor_image")
 */
class Image extends BaseFile
{
    private $uploadedBy;

    public function __construct(FileId $anId, FileName $aName, FileMimeType $aMimeType, $uploadedBy)
    {
        parent::__construct($anId, $aName, $aMimeType);
        $this->uploadedBy = $uploadedBy;
    }

    public function uploadedBy()
    {
        return $this->uploadedBy;
    }
}
```

```php
// src/AppBundle/Application/Command/UploadImageCommand.php

namespace AppBundle\Application\Command;

use BenGorFile\File\Application\Command\Upload\UploadFileCommand;

class UploadImageCommand extends UploadFileCommand
{
    private $uploadedBy;

    public function __construct($aName, $anUploadedFile, $aMimeType, $uploadedBy, $anId = null)
    {
        parent::__construct($aName, $anUploadedFile, $aMimeType, $anId);
        $this->uploadedBy = $uploadedBy;
    }

    public function uploadedBy()
    {
        return $this->uploadedBy;
    }
}
```

```php
// src/AppBundle/Application/Command/UploadImageHandler.php

namespace AppBundle\Application\Command;

use AppBundle\Entity\Image;
use BenGorFile\File\Domain\Model\FileException;
use BenGorFile\File\Domain\Model\FileFactory;
use BenGorFile\File\Domain\Model\FileId;
use BenGorFile\File\Domain\Model\FileMimeType;
use BenGorFile\File\Domain\Model\FileName;
use BenGorFile\File\Domain\Model\FileRepository;
use BenGorFile\File\Domain\Model\Filesystem;

class UploadImageHandler
{
    private $filesystem;
    private $repository;

    public function __construct(Filesystem $filesystem, FileRepository $aRepository)
    {
        $this->filesystem = $filesystem;
        $this->repository = $aRepository;
    }

    public function __invoke(UploadImageCommand $aCommand)
    {
        $id = new FileId($aCommand->id());
        $file = $this->repository->fileOfId($id);
        if (null !== $file) {
            throw FileException::idAlreadyExists($id);
        }
        $name = new FileName($aCommand->name());
        if (true === $this->filesystem->has($name)) {
            throw FileException::alreadyExists($name);
        }

        $this->filesystem->write($name, $aCommand->uploadedFile());
        $file = new Image($id, $name, new FileMimeType($aCommand->mimeType()), $aCommand->uploadedBy());

        $this->repository->persist($file);
    }
}
```

```php
// src/AppBundle/Form/ImageType.php

namespace AppBundle\Form;

use AppBundle\Application\Command\UploadImageCommand;
use AppBundle\Entity\Image;
use BenGorFile\File\Domain\Model\FileId;
use BenGorFile\File\Domain\Model\FileRepository;
use BenGorFile\File\Infrastructure\CommandBus\FileCommandBus;
use BenGorFile\FileBundle\Form\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageType extends FileType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('uploadedBy')
            ->add('submit', SubmitType::class);
    }

    public function emptyData(FormInterface $form)
    {
        $file = $form->get('bengor_file')->getData();
        if (null === $file) {
            return null;
        }

        return new UploadImageCommand(
            $file->getClientOriginalName(),
            file_get_contents($file->getPathname()),
            $file->getMimeType(),
            $form->get('uploadedBy')->getData()
        );
    }
}
```

```php
// src/AppBundle/DependencyInjection/Compiler/OverrideCommandPass.php

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\Application\Command\UploadImageCommand;
use AppBundle\Application\Command\UploadImageHandler;
use SimpleBus\SymfonyBridge\DependencyInjection\Compiler\RegisterHandlers;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OverrideCommandPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('bengor.file.application.command.upload_image')) {

            $container->findDefinition('bengor.file.application.command.upload_image')
                ->setClass(UploadImageHandler::class)->clearTags()->addTag(
                    'bengor_file_image_command_bus_handler', [
                    'handles' => UploadImageCommand::class,
                ]);

            // Because apart the handler the command is changed too, we need to relaunch
            // the Simple bus compiler pass to recollect the services with updated tags
            (new RegisterHandlers(
                'bengor_file.simple_bus_bridge_bundle.image_command_bus.command_handler_map',
                'bengor_file_image_command_bus_handler',
                'handles'
            ))->process($container);
        }
    }
}
```

```php
// src/AppBundle/AppBundle.php

namespace AppBundle;

use AppBundle\DependencyInjection\Compiler\OverrideCommandPass;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new OverrideCommandPass(), PassConfig::TYPE_OPTIMIZE);
    }
}
```

```php
// src/AppBundle/Controller/DefaultController.php

namespace AppBundle\Controller;

use AppBundle\Form\ImageType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/upload/image/", name="app_upload_image")
     */
    public function uploadImageAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(ImageType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            try {
                $this->get('bengor_file.image.command_bus')->handle($form->getData());
                
                $this->addFlash('notice', 'Upload process has been successfully finished);
            } catch (FileException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('image/upload.html.twig', ['form' => $form->createView()]);
    }
```

```twig
{# app/Resources/views/image/upload.html.twig #}

{% extends 'base.html.twig' %}

{% block body %}
    {% if app.session.flashbag.peekAll|length > 0 %}
        {% for type, messages in app.session.flashbag.all %}
            {% for message in messages %}
                <div class="{{ type ? type : '' }}">
                    {{ message | trans }}
                </div>
            {% endfor %}
        {% endfor %}
    {% endif %}
    
    {{ form(form) }}
{% endblock %}
```

- Back to the [index](index.md).
