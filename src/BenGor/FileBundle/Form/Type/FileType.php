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

namespace BenGor\FileBundle\Form\Type;

use BenGor\File\Application\Service\UploadFileRequest;
use BenGor\File\Domain\Model\File;
use BenGor\File\Domain\Model\FileId;
use BenGor\File\Domain\Model\FileRepository;
use BenGor\File\Infrastructure\UploadedFile\Symfony\SymfonyUploadedFile as UploadedFile;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType as UploadedFileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Upload file form type.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 */
class FileType extends AbstractType implements DataMapperInterface
{
    /**
     * The fully qualified class
     * name of the form data class.
     *
     * @var string
     */
    private $dataClass;

    /**
     * The fully qualified class name of
     * the application service request.
     *
     * @var string
     */
    private $request;

    /**
     * The file repository.
     *
     * @var FileRepository
     */
    private $repository;

    /**
     * Constructor.
     *
     * @param FileRepository $repository The file repository
     */
    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->request = $options['request'];

        $builder
            ->add('uploaded_file', UploadedFileType::class, [
                'mapped' => false,
            ])
            ->add('name', TextType::class, [
                'required' => false,
            ])
            ->add('file', FileIdType::class, [
                'label'    => false,
                'required' => false,
            ])
            ->setDataMapper($this)
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
                $this->dataClass = null === $event->getForm()->get('file')->getData()
                    ? $this->request
                    : File::class;
            });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'data_class'      => $this->dataClass,
            'empty_data'      => null,
            'request'         => UploadFileRequest::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function mapDataToForms($data, $forms)
    {
        $forms = iterator_to_array($forms);
        $forms['name']->setData($data ? $data->name() : '');
        $forms['uploaded_file']->setData($data ? $data->uploadedFile() : null);
    }

    /**
     * {@inheritdoc}
     */
    public function mapFormsToData($forms, &$data)
    {
        $forms = iterator_to_array($forms);

        if (!$forms['file']->getData()) {
            $data = new $this->request(
                new UploadedFile(
                    $forms['uploaded_file']->getData()
                ),
                $forms['name']->getData()
            );
        } else {
            $data = $this->repository->fileOfId(
                new FileId(
                    $forms['file']->getData()
                )
            );
        }
    }
}
