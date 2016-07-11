#Upload File

This chapter is a simple *cookbook* of how to implement a simple upload file process that persists a file, with its
related image. For this example we have:
 * *Doctrine* persistence layer
 * *Gaufrette* filesystem
 * Light **file entity** that contains a `firstName`, `lastName` and `image`
 * **Image entity** that extends BengorFile and it [limits mime types](limit_mime_types.md) to image mime types

Before to start with this example we should follow the [**Basic configuration**](basic_configuration.md) chapter.

Now, we have to overwrite the `app/config/config.yml` *bengor_file* configuration block with the following info:
```yml
ben_gor_file:
    file_class:
        image:
            class: AppBundle\Entity\Image
            persistence: doctrine
            filesystem:                                     
                gaufrette: already_configured_gaufrette_filesystem
            routes:
                upload:
                    enabled: true
                    name: image_upload
                    path: /ajax/upload
```

The next code is related with the image and file entities.

*Image entity*:
```php
<?php

namespace AppBundle\Entity;

use BenGor\File\Domain\Model\File;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 */
class Image extends File
{
    public static function availableMimeTypes()
    {
        return [
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpeg',
            'png'  => 'image/png',
        ];
    }
}
```

*User entity*:
```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="file")
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
     * @ORM\Column(type="string", column="first_name", length=255)
     */
    private $firstName;
    
    /**
     * @ORM\Column(type="string", column="last_name", length=255)
     */
    private $lastName;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist"})
     */
    private $image;
    
    // Getters and setters
    (...)
}
```

Then, we have the file form type code. Note that the image form field is BenGor's FileType type. Also needs
`data-bengor-file-type` data-attribute to manage as a Bengor's FileType inside JavaScript. 
```php
<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\User;
use BenGor\FileBundle\Form\Type\FileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('image', FileType::class, ['attr' => ['data-bengor-file-type' => '']])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
```

Now we have to implement our **controller action** for example inside `AppBundle\Controller\DefaultController.php`:
```php
(...)

/**
 * @Route(name="bengor_file_upload_action", path="/upload", methods={"GET", "POST"})
 */
public function uploadAction(Request $request)
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

    return $this->render('upload.html.twig', [
        'form' => $form->createView(),
    ]);
}
```


Finally, this is the minimum *Twig* code we need to represent a simple view of our form. This file is located in
`app/Resources/views/upload.html.twig`. Keep in mind that path name must be the same of `routes -> upload -> name`
inside `ben_gor_file` configuration tree.
```twig
{% extends 'base.html.twig' %}

{% block body %}
    {{ form_start(form, {
        'attr': {
            'data-bengor-file-action': path('image_upload')
        }
    }) }}
    {{ form_widget(form) }}

    {{ form_end(form) }}
{% endblock %}

{% block javascripts %}
    <script src="//code.jquery.com/jquery-2.2.0.min.js"></script>
    <script src="{{ asset('bundles/bengorfile/js/upload.js') }}"></script>
{% endblock %}
```

- Back to the [index](index.md).
