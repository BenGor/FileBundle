#Upload standalone

```php
// src/AppBundle/Entity/Image.php

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
            ->add('submit', SubmitType::class);
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
