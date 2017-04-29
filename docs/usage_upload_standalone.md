# Upload standalone

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
        try {
            $fileData = $this->uploadAction($request, $this->get('bengor_file.file.command_bus'), 'file');
            
            $this->addFlash('notice', 'Upload process has been successfully finished);
        } catch (FileException $exception) {
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->render('image/upload.html.twig', $fileData);
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
    
    {% if filename is defined %}
        <p>{{ filename }}</p>
    {% endif %}

    <form enctype="multipart/form-data" method="post" action="{{ path('app_upload_image') }}">
        <input type="file" name="file"/>
        <input type="submit"/>
    </form>
{% endblock %}
```

- Back to the [index](index.md).
