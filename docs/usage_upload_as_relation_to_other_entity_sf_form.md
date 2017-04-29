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
// src/AppBundle/Form/UserType.php

namespace AppBundle\Form;

use AppBundle\Entity\User;
use BenGorFile\File\Domain\Model\FileId;
use BenGorFile\File\Domain\Model\FileRepository;
use BenGorFile\File\Infrastructure\CommandBus\FileCommandBus;
use BenGorFile\FileBundle\Form\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private $commandBus;
    private $repository;

    public function __construct(FileCommandBus $commandBus, FileRepository $repository)
    {
        $this->commandBus = $commandBus;
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('file', FileType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'empty_data' => function (FormInterface $form) {
                $command = $form->get('file')->getData();
                $this->commandBus->handle($command);
                $file = $this->repository->fileOfId(new FileId($command->id()));
                $user = new User();
                $user->setName($form->get('name')->getData());
                $user->setPhoto($file);

                return $user;
            },
        ]);
    }
}
```

```yml
# app/config/services.yml

services:
    app_user_form_type:
        class: AppBundle\Form\UserType
        arguments:
            - '@bengor_file.file.command_bus'
            - '@bengor_file.file.repository'
        tags:
            - { name: form.type }
```

```php
// src/AppBundle/Controller/DefaultController.php

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/user/new/", name="app_user_new")
     */
    public function createUserAction(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $manager->persist($form->getData());
            $manager->flush();
        }

        return $this->render('user/new.html.twig', ['form' => $form->createView()]);
    }
}
```

```twig
{# app/Resources/views/user/new.html.twig #}

{% extends 'base.html.twig' %}

{% block body %}
    {{ form(form) }}
{% endblock %}
```

- Back to the [index](index.md).
