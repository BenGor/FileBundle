# Upload as relation to other entity

```php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(name="id", type="string", length=36)
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\File", cascade={"persist"})
     */
    private $file;

    // Getters and Setters
}
```

```php
// src/AppBundle/Entity/File.php

namespace AppBundle\Entity;

use BenGorFile\File\Domain\Model\File as BaseFile;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="bengor_file")
 */
class File extends BaseFile
{
}
```

```php
// src/AppBundle/Controller/DefaultController.php

namespace AppBundle\Controller;

use BenGorFile\FileBundle\Controller\UploadAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    use UploadAction;

    /**
     * @Route("/user/new/", name="app_user_new")
     */
    public function createUserAction(Request $request)
    {
        $fileData = [];
        if ($request->getMethod() === 'POST') {
            $fileData = $this->uploadAction($request, $this->get('bengor_file.file.command_bus'), 'file');
            $file = $this->get('bengor_file.file.repository')->fileOfId(new FileId($fileData['id']));
            
            $user = new User();
            $user
                ->setName($request->request->get('name'))
                ->setFile($file);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
        }

        return $this->render('user/new.html.twig', $fileData);
    }
}
```

```twig
{# app/Resources/views/user/new.html.twig #}

{% extends 'base.html.twig' %}

{% block body %}
    {% if filename is defined %}
        <p>{{ filename }}</p>
    {% endif %}

    <form enctype="multipart/form-data" method="post" action="{{ path('app_user_new') }}">
        <input type="text" name="name"/>
        <input type="file" name="file"/>
        <input type="submit"/>
    </form>
{% endblock %}
```

- Back to the [index](index.md).
